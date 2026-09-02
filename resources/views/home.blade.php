@extends('layouts.app')
@section('title', 'MarketKu - Belanja Online Terpercaya')

@section('content')
<!-- HERO -->
<section class="bg-gradient-to-r from-primary to-orange-400">
    <div class="max-w-7xl mx-auto px-4 py-12 md:py-20 flex flex-col md:flex-row items-center gap-8">
        <div class="text-white flex-1 text-center md:text-left">
            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-4">Belanja Mudah,<br>Chat Langsung Penjual</h1>
            <p class="text-white/90 mb-6 max-w-md mx-auto md:mx-0">Temukan ribuan produk pilihan dengan harga terbaik. Pesan cepat langsung lewat WhatsApp.</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-white text-primary font-bold px-8 py-3 rounded-full hover:bg-gray-100 transition">Belanja Sekarang</a>
        </div>
        <div class="flex-1 hidden md:block">
            <img src="https://placehold.co/500x350/ffffff/FF5722?text=MarketKu" class="rounded-2xl shadow-2xl" alt="hero">
        </div>
    </div>
</section>

<!-- CATEGORIES -->
<section class="max-w-7xl mx-auto px-4 py-10">
    <h2 class="text-xl md:text-2xl font-bold mb-5">Kategori Pilihan</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
        @foreach($categories as $cat)
            <a href="{{ route('products.index', ['category' => $cat->id]) }}"
               class="bg-white border border-gray-100 rounded-2xl p-4 text-center card-hover">
                <div class="w-14 h-14 mx-auto rounded-full bg-primary/10 flex items-center justify-center mb-2 text-primary font-bold text-lg">
                    {{ mb_substr($cat->name, 0, 1) }}
                </div>
                <p class="text-sm font-semibold">{{ $cat->name }}</p>
                <p class="text-xs text-gray-400">{{ $cat->products_count }} produk</p>
            </a>
        @endforeach
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl md:text-2xl font-bold">Produk Terbaru</h2>
        <a href="{{ route('products.index') }}" class="text-primary text-sm font-semibold hover:underline">Lihat Semua &rarr;</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
        @forelse($featured as $product)
            <x-product-card :product="$product" />
        @empty
            <p class="col-span-full text-center text-gray-400 py-10">Belum ada produk.</p>
        @endforelse
    </div>
</section>
@endsection
