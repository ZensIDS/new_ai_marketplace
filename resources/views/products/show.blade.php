@extends('layouts.app')
@section('title', $product->name . ' - MarketKu')

@section('content')
    @php
        $waNumber = config('services.whatsapp.number', '62895399259868');
        $userName = auth()->check() ? auth()->user()->name : '';
        $userEmail = auth()->check() ? auth()->user()->email : '';
        $hasVariants = $product->variants->isNotEmpty();
        $firstVariant = $hasVariants ? $product->variants->sortBy('price')->first() : null;
    @endphp

    <div class="max-w-6xl mx-auto px-4 py-8">
        <nav class="text-xs text-gray-400 mb-5">
            <a href="{{ route('home') }}" class="hover:text-primary">Home</a> /
            <a href="{{ route('products.index') }}" class="hover:text-primary">Produk</a> /
            <span class="text-gray-600">{{ $product->name }}</span>
        </nav>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Gambar -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden aspect-square">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>

            <!-- Info -->
            <div>
                <span class="text-xs font-semibold text-primary">{{ $product->category->name ?? '' }}</span>
                <h1 class="text-2xl font-bold mt-1 mb-2">{{ $product->name }}</h1>

                <p id="detail-price" class="text-3xl font-extrabold text-primary mb-1">
                    {{ $firstVariant ? $firstVariant->formatted_price : $product->formatted_price }}
                </p>
                <p class="text-sm text-gray-400 mb-4">
                    Stok: <span id="detail-stock">{{ $firstVariant ? $firstVariant->stock : $product->stock }}</span>
                </p>

                <div class="bg-gray-50 rounded-xl p-4 mb-5">
                    <p class="font-semibold text-sm mb-1">Deskripsi Produk</p>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $product->description }}</p>
                </div>

                @if ($hasVariants)
                    <div class="mb-6">
                        <p class="font-semibold text-sm mb-2">Pilih Varian</p>
                        <div class="flex flex-wrap gap-2" id="variant-list">
                            @foreach ($product->variants as $variant)
                                <button type="button"
                                    class="variant-btn border rounded-xl px-4 py-2 text-sm font-medium transition {{ $loop->first ? 'border-primary bg-primary-light text-primary-dark' : 'border-gray-200 text-gray-600 hover:border-primary' }}"
                                    data-id="{{ $variant->id }}" data-price="{{ $variant->price }}"
                                    data-price-formatted="{{ $variant->formatted_price }}"
                                    data-stock="{{ $variant->stock }}" onclick="selectVariant(this)">
                                    {{ $variant->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <input type="hidden" id="selected-variant-id" value="{{ $firstVariant?->id ?? 0 }}">

                @guest
                    <a href="{{ route('login') }}"
                        class="block w-full text-center bg-primary hover:bg-primary-dark text-dark text-base font-bold py-3.5 rounded-xl transition">
                        Beli Sekarang
                    </a>
                    <p class="text-xs text-gray-400 mt-2 text-center">Masuk atau daftar akun untuk melanjutkan pembelian.</p>
                @else
                    @if (auth()->user()->isCustomer())
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" id="detail-add-cart-btn" onclick="addToCartAjax(this)"
                                data-url="{{ route('cart.add', $product) }}" data-image="{{ $product->image_url }}"
                                data-variant-id="{{ $firstVariant?->id ?? 0 }}"
                                class="w-full flex items-center justify-center gap-2 border border-primary text-primary hover:bg-primary-light text-sm font-bold py-3.5 rounded-xl transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17" />
                                </svg>
                                Keranjang
                            </button>
                            <a href="#" id="detail-buy-btn" target="_blank"
                                class="w-full text-center bg-primary hover:bg-primary-dark text-dark text-sm font-bold py-3.5 rounded-xl transition">
                                Beli Sekarang
                            </a>
                        </div>
                    @else
                        <span
                            class="block w-full text-center bg-gray-100 text-gray-400 text-sm font-semibold py-3.5 rounded-xl">Mode
                            Admin</span>
                    @endif
                @endguest
            </div>
        </div>

        @if ($related->isNotEmpty())
            <div class="mt-14">
                <h2 class="text-xl font-bold mb-5">Produk Terkait</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
                    @foreach ($related as $rp)
                        <x-product-card :product="$rp" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @auth
        @if (auth()->user()->isCustomer())
            @push('scripts')
                <script>
                    const waNumber = "{{ $waNumber }}";
                    const userName = @json($userName);
                    const userEmail = @json($userEmail);
                    const productName = @json($product->name);
                    const productDesc = @json(\Illuminate\Support\Str::limit(strip_tags($product->description), 100));

                    function buildWaLink(priceFormatted) {
                        const lines = [
                            `Halo, saya ${userName} dengan akun (${userEmail}) ingin bertanya tentang produk ini:`,
                            '',
                            `🛍️ *${productName}*`,
                            `💰 ${priceFormatted}`,
                            `📝 ${productDesc}`,
                            `Mohon bantuannya ya, terima kasih.`,
                        ];
                        return `https://wa.me/${waNumber}?text=${encodeURIComponent(lines.join('\n'))}`;
                    }

                    function selectVariant(btn) {
                        document.querySelectorAll('.variant-btn').forEach(b => {
                            b.classList.remove('border-primary', 'bg-primary-light', 'text-primary-dark');
                            b.classList.add('border-gray-200', 'text-gray-600');
                        });
                        btn.classList.remove('border-gray-200', 'text-gray-600');
                        btn.classList.add('border-primary', 'bg-primary-light', 'text-primary-dark');

                        document.getElementById('detail-price').innerText = btn.dataset.priceFormatted;
                        document.getElementById('detail-stock').innerText = btn.dataset.stock;
                        document.getElementById('selected-variant-id').value = btn.dataset.id;

                        const cartBtn = document.getElementById('detail-add-cart-btn');
                        if (cartBtn) cartBtn.dataset.variantId = btn.dataset.id;

                        const buyBtn = document.getElementById('detail-buy-btn');
                        if (buyBtn) buyBtn.href = buildWaLink(btn.dataset.priceFormatted);
                    }

                    // set link awal saat halaman load
                    document.addEventListener('DOMContentLoaded', function() {
                        const initialPrice = document.getElementById('detail-price').innerText.trim();
                        const buyBtn = document.getElementById('detail-buy-btn');
                        if (buyBtn) buyBtn.href = buildWaLink(initialPrice);
                    });
                </script>
            @endpush
        @endif
    @endauth
@endsection
