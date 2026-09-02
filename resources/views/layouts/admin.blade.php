<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - MarketKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #F3F4F6;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #0B0B0C;
        }

        .sidebar a {
            color: #D1D5DB;
            padding: .7rem 1.2rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            border-radius: .5rem;
            margin: 0 .6rem;
            font-size: .92rem;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #C9A227;
            color: #0B0B0C;
            text-decoration: none;
            font-weight: 600;
        }

        .brand {
            color: #fff;
            font-weight: 800;
            font-size: 1.3rem;
            padding: 1.2rem;
        }

        .brand span {
            color: #C9A227;
        }

        .card-stat {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        }

        .table thead {
            background: #F9FAFB;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1050;
                left: -260px;
                transition: .3s;
            }

            .sidebar.show {
                left: 0;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="d-flex">
        <aside class="sidebar" id="sidebar">
            <div class="brand">Market<span>Ku</span> <small class="d-block text-white-50 fs-6 fw-normal">Admin
                    Panel</small></div>
            <nav class="d-flex flex-column gap-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Kategori
                </a>
                <a href="{{ route('admin.products.index') }}"
                    class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Produk
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> User (Admin & Customer)
                </a>
                <a href="{{ route('home') }}" target="_blank">
                    <i class="bi bi-globe"></i> Lihat Website
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button class="btn w-100 text-start" style="color:#D1D5DB; padding:.7rem 1.2rem;">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </button>
                </form>
            </nav>
        </aside>

        <div class="flex-fill">
            <header class="bg-white shadow-sm d-flex align-items-center justify-content-between px-4 py-3">
                <button class="btn d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h5 class="mb-0 fw-bold">@yield('title', 'Dashboard')</h5>
                <div class="text-end">
                    <p class="mb-0 fw-semibold small">{{ auth()->user()->name }}</p>
                    <p class="mb-0 text-muted small">Administrator</p>
                </div>
            </header>

            <main class="p-4">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
