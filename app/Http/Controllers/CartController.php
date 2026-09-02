<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Cart disimpan di session sebagai array: [product_id => qty]

    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $qty = max(1, (int) $request->input('qty', 1));

        if (isset($cart[$product->id])) {
            $cart[$product->id] += $qty;
        } else {
            $cart[$product->id] = $qty;
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $products = Product::with('category')->whereIn('id', array_keys($cart))->get();

        $items = $products->map(function ($product) use ($cart) {
            $product->qty = $cart[$product->id];
            $product->subtotal = $product->qty * (float) $product->price;
            return $product;
        });

        return view('cart.index', compact('items'));
    }

    public function updateQty(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $qty = max(1, (int) $request->input('qty', 1));

        if (isset($cart[$product->id])) {
            $cart[$product->id] = $qty;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }
}
