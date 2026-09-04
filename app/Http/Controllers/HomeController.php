<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $featured = Product::with(['category', 'variants'])->where('is_active', true)->latest()->take(8)->get();

        return view('home', compact('categories', 'featured'));
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

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->get();

        return view('products.index', compact('products', 'categories'));
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
