@props(['product'])

@php
    $waNumber = config('services.whatsapp.number', '62895399259868');
    $waText =
        'Halo, saya ingin bertanya tentang produk ini:%0A%0A' .
        "🛍️ *{$product->name}*%0A" .
        '💰 ' .
        $product->formatted_price .
        '%0A' .
        '📝 ' .
        \Illuminate\Support\Str::limit(strip_tags($product->description), 100) .
        '%0A%0A' .
        'Saya ingin bertanya tentang produk ini.';
    $waLink = "https://wa.me/{$waNumber}?text={$waText}";
@endphp

<div class="bg-white rounded-2xl overflow-hidden border border-gray-100 card-hover flex flex-col">
    <a href="#" class="block relative aspect-square overflow-hidden bg-gray-100">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @if ($product->stock <= 5 && $product->stock > 0)
            <span
                class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-[10px] font-bold px-2 py-1 rounded-full">Stok
                Terbatas</span>
        @elseif($product->stock == 0)
            <span
                class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full">Habis</span>
        @endif
    </a>
    <div class="p-3 flex flex-col flex-1">
        <span class="text-[11px] text-primary font-medium mb-1">{{ $product->category->name ?? '' }}</span>
        <h3 class="font-semibold text-sm line-clamp-2 mb-1 min-h-[2.5rem]">{{ $product->name }}</h3>
        <p class="text-xs text-gray-400 line-clamp-2 mb-2">{{ $product->description }}</p>
        <p class="text-primary font-bold text-lg mt-auto mb-3">{{ $product->formatted_price }}</p>

        @guest
            {{-- GUEST: hanya 1 tombol Beli -> arahkan ke login --}}
            <a href="{{ route('login') }}"
                class="w-full text-center bg-primary hover:bg-primary-dark text-white text-sm font-semibold py-2.5 rounded-xl transition">
                Beli
            </a>
        @else
            @if (auth()->user()->isCustomer())
                {{-- CUSTOMER: 2 tombol - keranjang & beli langsung via WhatsApp --}}
                <div class="grid grid-cols-2 gap-2">
                    <form action="{{ route('cart.add', $product) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-1 border border-primary text-primary hover:bg-primary-light text-xs font-semibold py-2.5 rounded-xl transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17" />
                            </svg>
                            Keranjang
                        </button>
                    </form>
                    <a href="{{ $waLink }}" target="_blank"
                        class="w-full text-center bg-primary hover:bg-primary-dark text-white text-xs font-semibold py-2.5 rounded-xl transition">
                        Beli
                    </a>
                </div>
            @else
                {{-- fallback jika admin melihat frontend --}}
                <span
                    class="w-full inline-block text-center bg-gray-100 text-gray-400 text-sm font-semibold py-2.5 rounded-xl">Mode
                    Admin</span>
            @endif
        @endguest
    </div>
</div>
