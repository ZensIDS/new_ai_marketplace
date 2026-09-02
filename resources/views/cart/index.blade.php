@extends('layouts.app')
@section('title', 'Keranjang Saya - MarketKu')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8 pb-32">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold">Keranjang Saya</h1>
            <a href="{{ route('products.index') }}"
                class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali Belanja
            </a>
        </div>

        @if ($items->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
                <p class="text-gray-400 mb-4">Keranjang kamu masih kosong.</p>
                <a href="{{ route('products.index') }}"
                    class="inline-block bg-primary text-dark px-6 py-2.5 rounded-full font-bold hover:bg-primary-dark">Mulai
                    Belanja</a>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 divide-y divide-gray-100">
                <!-- select all -->
                <div class="p-4 flex items-center gap-3 text-sm font-semibold text-gray-500">
                    <input type="checkbox" id="select-all" checked class="w-5 h-5 accent-[#C9A227] rounded">
                    <label for="select-all">Pilih Semua ({{ $items->count() }} produk)</label>
                </div>

                @foreach ($items as $item)
                    <div class="p-4 flex items-center gap-4 cart-item" data-key="{{ $item->key }}"
                        data-name="{{ $item->product->name }}{{ $item->variant ? ' (' . $item->variant->name . ')' : '' }}"
                        data-price="{{ $item->unit_price }}" data-qty="{{ $item->qty }}"
                        data-subtotal="{{ $item->subtotal }}">
                        <input type="checkbox" class="item-check w-5 h-5 accent-[#C9A227] rounded shrink-0" checked>
                        <img src="{{ $item->product->image_url }}"
                            class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover shrink-0"
                            alt="{{ $item->product->name }}">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm md:text-base line-clamp-2">{{ $item->product->name }}</p>
                            @if ($item->variant)
                                <p class="text-xs text-primary font-medium">Varian: {{ $item->variant->name }}</p>
                            @endif
                            <p class="text-xs text-gray-400">{{ $item->product->category->name ?? '' }}</p>
                            <p class="text-primary font-bold mt-1">Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <form action="{{ route('cart.update', $item->key) }}" method="POST"
                                class="flex items-center gap-1">
                                @csrf
                                <input type="number" name="qty" value="{{ $item->qty }}" min="1"
                                    class="w-14 border border-gray-200 rounded-lg text-center text-sm py-1"
                                    onchange="this.form.submit()">
                            </form>
                            <form action="{{ route('cart.remove', $item->key) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-500 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if ($items->isNotEmpty())
        <!-- Widget bawah seperti Shopee -->
        <div
            class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-[0_-4px_16px_rgba(0,0,0,.08)] z-40">
            <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
                <div class="text-sm">
                    <p class="text-gray-500"><span id="selected-count">{{ $items->count() }}</span> barang dipilih</p>
                    <p class="font-bold text-lg text-primary">Total: Rp <span id="selected-total">0</span></p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('products.index') }}"
                        class="hidden sm:inline-block text-center border border-gray-200 text-gray-600 hover:border-primary hover:text-primary font-semibold px-5 py-3 rounded-full transition">
                        + Produk Lain
                    </a>
                    <button id="checkout-btn"
                        class="bg-primary hover:bg-primary-dark text-dark font-bold px-6 md:px-10 py-3 rounded-full transition disabled:opacity-40">
                        Checkout via WhatsApp
                    </button>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            const waNumber = "{{ config('services.whatsapp.number', '62895399259868') }}";
            const userName = @json(auth()->user()->name ?? '');
            const userEmail = @json(auth()->user()->email ?? '');

            function formatRupiah(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }

            function recalc() {
                let total = 0,
                    count = 0;
                document.querySelectorAll('.cart-item').forEach(item => {
                    const check = item.querySelector('.item-check');
                    if (check.checked) {
                        total += parseFloat(item.dataset.subtotal);
                        count++;
                    }
                });
                document.getElementById('selected-total').innerText = formatRupiah(total);
                document.getElementById('selected-count').innerText = count;
                document.getElementById('checkout-btn').disabled = count === 0;
            }

            document.querySelectorAll('.item-check').forEach(cb => cb.addEventListener('change', () => {
                recalc();
                const allChecked = [...document.querySelectorAll('.item-check')].every(c => c.checked);
                document.getElementById('select-all').checked = allChecked;
            }));

            const selectAll = document.getElementById('select-all');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    document.querySelectorAll('.item-check').forEach(cb => cb.checked = this.checked);
                    recalc();
                });
            }

            const checkoutBtn = document.getElementById('checkout-btn');
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', function() {
                    let lines = [`Halo, saya ${userName} dengan akun (${userEmail}) ingin bertanya tentang produk ini:`,
                        ""
                    ];
                    document.querySelectorAll('.cart-item').forEach(item => {
                        const check = item.querySelector('.item-check');
                        if (check.checked) {
                            const qty = item.dataset.qty;
                            const name = item.dataset.name;
                            const subtotal = parseFloat(item.dataset.subtotal);
                            lines.push(`🛍️ ${name} x${qty} - Rp ${formatRupiah(subtotal)}`);
                        }
                    });
                    lines.push(`Mohon bantuannya ya, terima kasih.`);

                    const text = encodeURIComponent(lines.join("\n"));
                    window.open(`https://wa.me/${waNumber}?text=${text}`, '_blank');
                });
            }

            recalc();
        </script>
    @endpush
@endsection
