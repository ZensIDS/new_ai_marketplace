<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Cart disimpan di session sebagai array dengan key komposit "productId_variantId":
    // ['5_12' => ['product_id' => 5, 'variant_id' => 12, 'qty' => 2], '7_0' => [...tanpa varian...]]

    public function add(Request $request, Product $product)
    {
        $variantId = (int) $request->input('variant_id', 0);
        $qty = max(1, (int) $request->input('qty', 1));

        // Kalau produk punya varian tapi tidak dikirim variant_id, pakai varian termurah otomatis
        if ($variantId === 0 && $product->variants()->exists()) {
            $variantId = (int) $product->variants()->orderBy('price')->value('id');
        }

        $key = $product->id . '_' . $variantId;
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'variant_id' => $variantId,
                'qty' => $qty,
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cart_count' => count($cart),
                'message' => 'Produk ditambahkan ke keranjang.',
            ]);
        }

        return redirect()->route('cart.index');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $productIds = collect($cart)->pluck('product_id')->unique();
        $products = Product::with(['category', 'variants'])->whereIn('id', $productIds)->get()->keyBy('id');

        $items = collect($cart)->map(function ($row, $key) use ($products) {
            $product = $products->get($row['product_id']);
            if (!$product) {
                return null;
            }

            $variant = $row['variant_id']
                ? $product->variants->firstWhere('id', $row['variant_id'])
                : null;

            $unitPrice = $variant ? (float) $variant->price : (float) $product->price;

            return (object) [
                'key' => $key,
                'product' => $product,
                'variant' => $variant,
                'qty' => $row['qty'],
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice * $row['qty'],
            ];
        })->filter()->values();

        return view('cart.index', compact('items'));
    }

    public function updateQty(Request $request, string $key)
    {
        $cart = session()->get('cart', []);
        $qty = max(1, (int) $request->input('qty', 1));

        if (isset($cart[$key])) {
            $cart[$key]['qty'] = $qty;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function changeVariant(Request $request, string $key)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$key])) {
            return redirect()->route('cart.index');
        }

        $row = $cart[$key];
        $variant = ProductVariant::where('id', (int) $request->input('variant_id'))
            ->where('product_id', $row['product_id'])
            ->firstOrFail();

        $newKey = $row['product_id'] . '_' . $variant->id;

        if ($newKey === $key) {
            return redirect()->route('cart.index');
        }

        if (isset($cart[$newKey])) {
            // varian tujuan sudah ada di keranjang -> gabungkan qty
            $cart[$newKey]['qty'] += $row['qty'];
            unset($cart[$key]);
        } else {
            // ganti varian dengan mempertahankan urutan item di keranjang
            $rebuilt = [];
            foreach ($cart as $k => $r) {
                if ($k === $key) {
                    $rebuilt[$newKey] = [
                        'product_id' => $row['product_id'],
                        'variant_id' => $variant->id,
                        'qty' => $row['qty'],
                    ];
                } else {
                    $rebuilt[$k] = $r;
                }
            }
            $cart = $rebuilt;
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    public function remove(string $key)
    {
        $cart = session()->get('cart', []);
        unset($cart[$key]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }
}