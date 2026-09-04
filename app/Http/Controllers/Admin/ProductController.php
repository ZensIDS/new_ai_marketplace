<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'tags']);

        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('tags', function ($tagQuery) use ($search) {
                      $tagQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('related_keywords', 'like', "%{$search}%");
                  });
            });
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'variants' => ['nullable', 'array'],
            'variants.*.name' => ['required_with:variants', 'string', 'max:100'],
            'variants.*.price' => ['required_with:variants', 'numeric', 'min:0'],
            'variants.*.stock' => ['required_with:variants', 'integer', 'min:0'],
            'tags_input' => ['nullable', 'string'],
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
        $data['is_active'] = $request->boolean('is_active', true);
        $variants = $data['variants'] ?? [];
        $tags = preg_split('/[,\n]+/', $data['tags_input'] ?? '');
        unset($data['variants'], $data['tags_input']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);
        $this->syncVariants($product, $variants);
        $this->syncTags($product, $tags);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $tags = Tag::orderBy('name')->get();
        $product->load(['variants', 'tags']);
        return view('admin.products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'variants' => ['nullable', 'array'],
            'variants.*.name' => ['required_with:variants', 'string', 'max:100'],
            'variants.*.price' => ['required_with:variants', 'numeric', 'min:0'],
            'variants.*.stock' => ['required_with:variants', 'integer', 'min:0'],
            'tags_input' => ['nullable', 'string'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $variants = $data['variants'] ?? [];
        $tags = preg_split('/[,\n]+/', $data['tags_input'] ?? '');
        unset($data['variants'], $data['tags_input']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        $this->syncVariants($product, $variants);
        $this->syncTags($product, $tags);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Hapus semua varian lama lalu simpan ulang dari input form (repeater).
     */
    protected function syncVariants(Product $product, array $variants): void
    {
        $product->variants()->delete();

        foreach (array_values($variants) as $i => $variant) {
            if (empty($variant['name'])) {
                continue;
            }

            $product->variants()->create([
                'name' => $variant['name'],
                'price' => $variant['price'],
                'stock' => $variant['stock'] ?? 0,
                'sort_order' => $i,
            ]);
        }
    }

    protected function syncTags(Product $product, array $tagNames): void
    {
        $tagIds = collect($tagNames)
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique(fn ($name) => Str::lower($name))
            ->map(function ($name) {
                $tag = Tag::firstOrCreate(
                    ['name' => $name],
                    ['slug' => Str::slug($name)]
                );

                return $tag->id;
            })
            ->values()
            ->all();

        $product->tags()->sync($tagIds);
    }


}
