<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->get();
        $featured = $this->recommendedProducts($request, 12);

        return view('home', compact('categories', 'featured'));
    }

    /**
     * Rekomendasi beranda yang berbeda tiap pengunjung/perangkat.
     * - ID pengunjung disimpan di cookie (1 tahun), jadi hasil stabil saat refresh.
     * - Seed ikut berganti tiap hari supaya beranda terasa segar.
     * - Produk dipilih bergiliran antar kategori agar hasilnya bervariasi.
     */
    private function recommendedProducts(Request $request, int $limit)
    {
        $visitorId = $request->cookie('visitor_id');

        if (! $visitorId) {
            $visitorId = Str::random(20);
            Cookie::queue('visitor_id', $visitorId, 60 * 24 * 365);
        }

        $seed = $visitorId . date('Ymd');

        $groups = Product::where('is_active', true)->get(['id', 'category_id'])
            ->groupBy('category_id')
            ->map(fn ($g) => $g->sortBy(fn ($p) => crc32($seed . '-' . $p->id))->pluck('id')->values())
            ->sortBy(fn ($g, $categoryId) => crc32($seed . 'c' . $categoryId))
            ->values();

        $ids = [];
        $round = 0;

        while (count($ids) < $limit && $groups->contains(fn ($g) => isset($g[$round]))) {
            foreach ($groups as $g) {
                if (isset($g[$round]) && count($ids) < $limit) {
                    $ids[] = $g[$round];
                }
            }
            $round++;
        }

        $products = Product::with(['category', 'variants'])->whereIn('id', $ids)->get()->keyBy('id');

        return collect($ids)->map(fn ($id) => $products[$id] ?? null)->filter()->values();
    }

    public function products(Request $request)
    {
        $query = Product::with(['category', 'variants'])->where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('q')) {
            $search = trim($request->q);
            $tokens = collect(preg_split('/\s+/', $search))
                ->map(fn ($token) => trim($token))
                ->filter(fn ($token) => mb_strlen($token) >= 2)
                ->unique()
                ->values();

            if ($tokens->isNotEmpty()) {
                $query->where(function ($outer) use ($tokens) {
                    foreach ($tokens as $token) {
                        $like = '%' . $token . '%';
                        $outer->where(function ($q) use ($like) {
                            $q->where('products.name', 'like', $like)
                              ->orWhere('products.description', 'like', $like)
                              ->orWhereHas('tags', function ($tagQuery) use ($like) {
                                  $tagQuery->where('tags.name', 'like', $like)
                                           ->orWhere('tags.related_keywords', 'like', $like);
                              })
                              ->orWhereHas('category', function ($categoryQuery) use ($like) {
                                  $categoryQuery->where('categories.name', 'like', $like);
                              });
                        });
                    }
                });

                $bindings = [];
                $scoreParts = [];

                // Exact phrase gets the biggest boost.
                $phraseLike = '%' . $search . '%';
                $scoreParts[] = "CASE WHEN products.name LIKE ? THEN 100 ELSE 0 END";
                $bindings[] = $phraseLike;
                $scoreParts[] = "CASE WHEN products.description LIKE ? THEN 60 ELSE 0 END";
                $bindings[] = $phraseLike;

                foreach ($tokens as $token) {
                    $like = '%' . $token . '%';
                    $scoreParts[] = "CASE WHEN products.name LIKE ? THEN 50 ELSE 0 END";
                    $bindings[] = $like;
                    $scoreParts[] = "CASE WHEN products.description LIKE ? THEN 25 ELSE 0 END";
                    $bindings[] = $like;
                    $scoreParts[] = "CASE WHEN EXISTS (SELECT 1 FROM product_tag pt INNER JOIN tags t ON t.id = pt.tag_id WHERE pt.product_id = products.id AND t.name LIKE ?) THEN 45 ELSE 0 END";
                    $bindings[] = $like;
                    $scoreParts[] = "CASE WHEN EXISTS (SELECT 1 FROM product_tag pt INNER JOIN tags t ON t.id = pt.tag_id WHERE pt.product_id = products.id AND t.related_keywords LIKE ?) THEN 30 ELSE 0 END";
                    $bindings[] = $like;
                    $scoreParts[] = "CASE WHEN EXISTS (SELECT 1 FROM categories c WHERE c.id = products.category_id AND c.name LIKE ?) THEN 15 ELSE 0 END";
                    $bindings[] = $like;
                }

                $query->select('products.*')
                    ->selectRaw('(' . implode(' + ', $scoreParts) . ') as relevance_score', $bindings)
                    ->orderByDesc('relevance_score')
                    ->orderByDesc('products.created_at');
            }
        } else {
            $query->latest('products.created_at');
        }

        $products = $query->paginate(20)->withQueryString();
        $categories = Category::withCount('products')->get();
        $relatedKeywords = $request->filled('q') ? $this->relatedKeywords(trim($request->q)) : collect();
        $popularKeywords = $this->popularKeywords();

        return view('products.index', compact('products', 'categories', 'relatedKeywords', 'popularKeywords'));
    }

    /**
     * Endpoint autocomplete untuk kotak cari (JSON).
     */
    public function suggest(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $popular = $this->popularKeywords();

        if (mb_strlen($q) < 2) {
            $categories = Category::orderBy('name')->limit(8)->get()
                ->map(fn ($c) => ['name' => $c->name, 'url' => route('products.index', ['category' => $c->id])]);

            return response()->json([
                'popular' => $popular, 'categories' => $categories, 'keywords' => [], 'products' => [],
            ]);
        }

        $ql = Str::lower($q);
        $like = '%' . addcslashes($q, '%_\\') . '%';
        $rank = fn (string $text) => str_starts_with(Str::lower($text), $ql) ? 0 : (str_contains(Str::lower($text), $ql) ? 1 : 2);

        $products = Product::with(['category', 'variants'])
            ->where('is_active', true)
            ->where(function ($w) use ($like) {
                $w->where('name', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('tags', fn ($t) => $t->where('tags.name', 'like', $like)
                        ->orWhere('tags.related_keywords', 'like', $like));
            })
            ->limit(30)->get()
            ->sortBy(fn ($p) => $rank($p->name))
            ->take(5)->values()
            ->map(fn ($p) => [
                'name' => $p->name,
                'url' => route('products.show', $p),
                'image' => $p->image_url,
                'price' => $p->formatted_display_price,
                'category' => $p->category->name ?? '',
            ]);

        $keywords = Tag::whereNotIn('name', ['AI', 'Generative AI'])
            ->where(fn ($t) => $t->where('name', 'like', $like)->orWhere('related_keywords', 'like', $like))
            ->get()
            ->flatMap(fn ($tag) => array_merge([$tag->name], $tag->related_keywords_array))
            ->map(fn ($k) => trim($k))
            ->filter(fn ($k) => $k !== '' && Str::lower($k) !== $ql)
            ->unique(fn ($k) => Str::lower($k))
            ->sortBy($rank)
            ->take(6)->values();

        $categories = Category::where('name', 'like', $like)->limit(3)->get()
            ->map(fn ($c) => ['name' => $c->name, 'url' => route('products.index', ['category' => $c->id])]);

        return response()->json(compact('popular', 'keywords', 'products', 'categories'));
    }

    private function popularKeywords()
    {
        return Tag::withCount('products')
            ->whereNotIn('name', ['AI', 'Generative AI'])
            ->orderByDesc('products_count')
            ->limit(8)
            ->pluck('name');
    }

    private function relatedKeywords(string $term)
    {
        $tokens = collect(preg_split('/\s+/', $term))
            ->map(fn ($t) => trim($t))
            ->filter(fn ($t) => mb_strlen($t) >= 2)
            ->values();

        if ($tokens->isEmpty()) {
            return collect();
        }

        $lowerTokens = $tokens->map(fn ($t) => Str::lower($t))->push(Str::lower($term));

        return Tag::withCount('products')
            ->whereNotIn('name', ['AI', 'Generative AI'])
            ->where(function ($w) use ($tokens) {
                foreach ($tokens as $token) {
                    $like = '%' . addcslashes($token, '%_\\') . '%';
                    $w->orWhere('name', 'like', $like)->orWhere('related_keywords', 'like', $like);
                }
            })
            ->orderByDesc('products_count')
            ->limit(6)->get()
            ->flatMap(fn ($tag) => array_merge([$tag->name], $tag->related_keywords_array))
            ->map(fn ($k) => trim($k))
            ->filter(fn ($k) => $k !== '' && ! $lowerTokens->contains(Str::lower($k)))
            ->unique(fn ($k) => Str::lower($k))
            ->take(10)->values();
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load(['category', 'variants']);

        $related = Product::with('variants')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}