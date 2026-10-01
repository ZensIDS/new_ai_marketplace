<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SATU AI - Marketplace Langganan AI Terpercaya')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#C9A227',
                            dark: '#A8841E',
                            light: '#FBF3DD'
                        },
                        dark: '#0B0B0C',
                        surface: '#151517',
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-hover {
            transition: all .25s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(201, 162, 39, .18);
        }

        .fly-clone {
            position: fixed;
            z-index: 9999;
            border-radius: 9999px;
            object-fit: cover;
            pointer-events: none;
            transition: all .7s cubic-bezier(.55, 0, .1, 1);
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- NAVBAR -->
    <header class="bg-dark sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-3 md:px-4 py-3 flex items-center gap-2 md:gap-4">
            <a href="{{ route('home') }}" class="shrink-0 flex items-center">
                <img src="{{ asset('images/logosatuai1.png') }}" alt="SATU AI"
                    class="h-9 md:h-12 w-auto object-contain">
            </a>

            {{-- Search box: tampil di mobile & desktop --}}
            <x-search-box variant="dark" class="flex flex-1 min-w-0" />

            <div class="flex items-center gap-1 md:gap-3 shrink-0">
                @auth
                    @if (auth()->user()->isCustomer())
                        <a href="{{ route('cart.index') }}" id="cart-icon"
                            class="relative p-2 rounded-full hover:bg-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span id="cart-badge"
                                class="absolute -top-1 -right-1 bg-primary text-dark text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center {{ session('cart') && count(session('cart')) ? '' : 'hidden' }}">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                        </a>
                    @endif
                @endauth

                {{-- Menu desktop --}}
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <div class="hidden sm:block text-sm text-right">
                            <p class="font-semibold leading-tight text-white">{{ auth()->user()->name }}</p>
                            <p class="text-gray-400 text-xs leading-tight">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-sm font-medium text-primary hover:underline hidden sm:inline">Admin Panel</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button
                                class="text-sm font-medium bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-full">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm font-medium px-4 py-2 rounded-full text-white hover:bg-white/10">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="text-sm font-medium bg-primary text-dark px-4 py-2 rounded-full hover:bg-primary-dark">Daftar</a>
                    @endauth
                </div>

                {{-- Tombol hamburger (mobile) --}}
                <button type="button" id="menu-btn" class="md:hidden p-2 rounded-full hover:bg-white/10 text-white"
                    aria-label="Buka menu" aria-controls="mobile-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- SIDEBAR MENU (mobile) -->
    <div id="mobile-menu" class="fixed inset-0 z-[100] hidden md:hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-black/60" data-menu-close></div>
        <aside id="menu-panel"
            class="absolute right-0 top-0 h-full w-72 max-w-[85%] bg-dark text-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300">
            <div class="flex items-center justify-between px-4 py-3 border-b border-white/10">
                <img src="{{ asset('images/logosatuai1.png') }}" alt="SATU AI" class="h-9 w-auto object-contain">
                <button type="button" data-menu-close class="p-2 rounded-full hover:bg-white/10" aria-label="Tutup menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @auth
                <div class="px-4 py-4 border-b border-white/10">
                    <p class="font-semibold leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-gray-400 text-xs">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
            @endauth

            <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-1 text-sm font-medium">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10">Beranda</a>
                <a href="{{ route('products.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10">Semua Produk</a>
                @auth
                    @if (auth()->user()->isCustomer())
                        <a href="{{ route('cart.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10">Keranjang</a>
                    @endif
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-primary hover:bg-white/10">Admin Panel</a>
                    @endif
                @endauth
            </nav>

            <div class="p-4 border-t border-white/10 space-y-2">
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="w-full text-sm font-medium bg-white/10 hover:bg-white/20 py-3 rounded-full">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block text-center text-sm font-medium border border-white/20 hover:bg-white/10 py-3 rounded-full">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="block text-center text-sm font-semibold bg-primary text-dark hover:bg-primary-dark py-3 rounded-full">Daftar</a>
                @endauth
            </div>
        </aside>
    </div>

    <main>
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg">
                    {{ session('success') }}</div>
            </div>
        @endif
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-dark text-gray-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="col-span-2 md:col-span-1">
                <div class="mb-3">
                    <img src="{{ asset('images/logosatuai1.png') }}" alt="SATU AI" class="h-20 w-auto object-contain">
                </div>
                <p class="text-sm text-gray-400">Marketplace terpercaya untuk berbagai langganan tools AI premium, cepat
                    dan mudah.</p>
            </div>
            <div>
                <p class="font-semibold text-white mb-3">Belanja</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('products.index') }}" class="hover:text-primary">Semua Produk</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-primary">Kategori</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold text-white mb-3">Akun</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('login') }}" class="hover:text-primary">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-primary">Daftar</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold text-white mb-3">Bantuan</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="https://wa.me/{{ config('services.whatsapp.number', '6281910101161') }}"
                            target="_blank" class="hover:text-primary">Hubungi via WhatsApp</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10 text-center text-xs text-gray-500 py-4">
            &copy; {{ date('Y') }} SATU AI. All rights reserved.
        </div>
    </footer>

    <script>
        // ==== Global helper: tambah ke keranjang via AJAX + animasi terbang ke icon cart ====
        function addToCartAjax(button) {
            const url = button.dataset.url;
            const imgSrc = button.dataset.image;
            const variantId = button.dataset.variantId || 0;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                document.querySelector('input[name="_token"]')?.value;

            flyToCart(button, imgSrc);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        variant_id: variantId,
                        qty: 1
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        updateCartBadge(data.cart_count);
                    }
                })
                .catch(() => {});
        }

        function flyToCart(button, imgSrc) {
            const cartIcon = document.getElementById('cart-icon');
            if (!cartIcon || !imgSrc) return;

            const startRect = button.getBoundingClientRect();
            const endRect = cartIcon.getBoundingClientRect();

            const clone = document.createElement('img');
            clone.src = imgSrc;
            clone.className = 'fly-clone';
            clone.style.width = '40px';
            clone.style.height = '40px';
            clone.style.left = (startRect.left + startRect.width / 2 - 20) + 'px';
            clone.style.top = (startRect.top + startRect.height / 2 - 20) + 'px';
            clone.style.opacity = '0.95';
            document.body.appendChild(clone);

            requestAnimationFrame(() => {
                clone.style.left = (endRect.left + endRect.width / 2 - 10) + 'px';
                clone.style.top = (endRect.top + endRect.height / 2 - 10) + 'px';
                clone.style.width = '20px';
                clone.style.height = '20px';
                clone.style.opacity = '0.2';
            });

            setTimeout(() => {
                clone.remove();
                cartIcon.classList.add('scale-125');
                setTimeout(() => cartIcon.classList.remove('scale-125'), 200);
            }, 700);
        }

        function updateCartBadge(count) {
            const badge = document.getElementById('cart-badge');
            if (!badge) return;
            badge.innerText = count;
            badge.classList.toggle('hidden', count <= 0);
        }
    </script>

    <script>
        (function() {
            const menu = document.getElementById('mobile-menu');
            const panel = document.getElementById('menu-panel');
            if (!menu || !panel) return;
            const lock = on => document.body.classList.toggle('overflow-hidden', on);
            const open = () => {
                menu.classList.remove('hidden');
                menu.setAttribute('aria-hidden', 'false');
                lock(true);
                requestAnimationFrame(() => requestAnimationFrame(() => panel.classList.remove('translate-x-full')));
            };
            const close = () => {
                panel.classList.add('translate-x-full');
                menu.setAttribute('aria-hidden', 'true');
                lock(false);
                setTimeout(() => menu.classList.add('hidden'), 300);
            };
            document.getElementById('menu-btn')?.addEventListener('click', open);
            document.querySelectorAll('[data-menu-close]').forEach(el => el.addEventListener('click', close));
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && !menu.classList.contains('hidden')) close();
            });
            window.matchMedia('(min-width: 768px)').addEventListener('change', e => {
                if (e.matches && !menu.classList.contains('hidden')) {
                    panel.classList.add('translate-x-full');
                    menu.classList.add('hidden');
                    lock(false);
                }
            });
        })();
    </script>
    @stack('scripts')
</body>

</html>