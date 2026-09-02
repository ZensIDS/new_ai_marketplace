<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MarketKu - Belanja Online Terpercaya')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#FF5722',
                            dark: '#E64A19',
                            light: '#FFF3EE'
                        },
                        dark: '#1F2937',
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
            box-shadow: 0 12px 24px rgba(0, 0, 0, .08);
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- NAVBAR -->
    <header class="bg-white sticky top-0 z-50 shadow-sm">
        <div class="bg-primary text-white text-xs py-1.5 text-center px-4">
            Belanja aman & mudah — chat langsung admin lewat WhatsApp!
        </div>
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-4">
            <a href="{{ route('home') }}" class="text-2xl font-extrabold text-primary shrink-0">Market<span
                    class="text-dark">Ku</span></a>

            <form action="{{ route('products.index') }}" method="GET" class="flex-1 hidden md:flex">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk impianmu..."
                    class="w-full border border-gray-200 rounded-l-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/40">
                <button class="bg-primary hover:bg-primary-dark text-white px-5 rounded-r-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                    </svg>
                </button>
            </form>

            <div class="flex items-center gap-3 ml-auto">
                @auth
                    @if (auth()->user()->isCustomer())
                        <a href="{{ route('cart.index') }}" class="relative p-2 rounded-full hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-dark" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @if (session('cart') && count(session('cart')))
                                <span
                                    class="absolute -top-1 -right-1 bg-primary text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    @endif
                    <div class="hidden sm:block text-sm text-right">
                        <p class="font-semibold leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-gray-400 text-xs leading-tight">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="text-sm font-medium text-primary hover:underline hidden sm:inline">Admin Panel</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            class="text-sm font-medium bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-full">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium px-4 py-2 rounded-full hover:bg-gray-100">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="text-sm font-medium bg-primary text-white px-4 py-2 rounded-full hover:bg-primary-dark">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

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
                <p class="text-2xl font-extrabold text-white mb-3">Market<span class="text-primary">Ku</span></p>
                <p class="text-sm text-gray-400">Marketplace terpercaya untuk kebutuhan belanja online kamu, cepat dan
                    mudah.</p>
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
                    <li><a href="https://wa.me/{{ config('services.whatsapp.number', '62895399259868') }}"
                            target="_blank" class="hover:text-primary">Hubungi via WhatsApp</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 text-center text-xs text-gray-500 py-4">
            &copy; {{ date('Y') }} MarketKu. All rights reserved.
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
