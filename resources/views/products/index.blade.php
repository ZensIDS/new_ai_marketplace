@extends('layouts.app')
@section('title', 'Semua Produk - SATU AI')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

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
                        @foreach ($categories as $cat)
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

                @if (request()->filled('q'))
                    <p class="text-sm text-gray-500 mb-3">Hasil untuk
                        <span class="font-semibold text-gray-800">“{{ request('q') }}”</span>
                    </p>
                    @if (!empty($relatedKeywords) && $relatedKeywords->isNotEmpty())
                        <div class="flex flex-wrap items-center gap-2 mb-5">
                            <span class="text-xs text-gray-400">Pencarian terkait:</span>
                            @foreach ($relatedKeywords as $kw)
                                <a href="{{ route('products.index', ['q' => $kw]) }}"
                                    class="text-xs px-3 py-1.5 rounded-full bg-white border border-gray-200 hover:border-primary hover:text-primary transition">{{ $kw }}</a>
                            @endforeach
                        </div>
                    @endif
                @endif

                <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-4">
                    @forelse($products as $product)
                        <x-product-card :product="$product" />
                    @empty
                        <div class="col-span-full text-center py-16">
                            <p class="text-gray-500 font-medium mb-1">Produk tidak ditemukan.</p>
                            @if (!empty($popularKeywords) && $popularKeywords->isNotEmpty())
                                <p class="text-sm text-gray-400 mb-4">Coba kata kunci populer ini:</p>
                                <div class="flex flex-wrap justify-center gap-2">
                                    @foreach ($popularKeywords as $kw)
                                        <a href="{{ route('products.index', ['q' => $kw]) }}"
                                            class="text-xs px-3 py-1.5 rounded-full bg-white border border-gray-200 hover:border-primary hover:text-primary transition">{{ $kw }}</a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection