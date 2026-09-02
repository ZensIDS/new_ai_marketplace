@extends('layouts.app')
@section('title', 'Semua Produk - MarketKu')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <!-- Search mobile -->
    <form action="{{ route('products.index') }}" method="GET" class="flex md:hidden mb-5">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..."
               class="w-full border border-gray-200 rounded-l-full px-4 py-2 text-sm focus:outline-none">
        <button class="bg-primary text-white px-4 rounded-r-full">Cari</button>
    </form>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Sidebar kategori -->
        <aside class="md:w-56 shrink-0">
            <div class="bg-white rounded-2xl border border-gray-100 p-4 sticky top-24">
                <p class="font-bold mb-3 text-sm">Kategori</p>
                <ul class="space-y-1 text-sm">
                    <li>
                        <a href="{{ route('products.index') }}"
                           class="block px-3 py-2 rounded-lg {{ !request('category') ? 'bg-primary/10 text-primary font-semibold' : 'hover:bg-gray-50' }}">
                           Semua Kategori
                        </a>
                    </li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('products.index', ['category' => $cat->id]) }}"
                               class="block px-3 py-2 rounded-lg {{ request('category') == $cat->id ? 'bg-primary/10 text-primary font-semibold' : 'hover:bg-gray-50' }}">
                               {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <!-- Grid produk + pagination -->
        <div class="flex-1">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-xl font-bold">Semua Produk</h1>
                <p class="text-sm text-gray-400">{{ $products->total() }} produk ditemukan</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
                @forelse($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <p class="col-span-full text-center text-gray-400 py-16">Produk tidak ditemukan.</p>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
