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

        .sidebar-header {
            position: relative;
        }

        .sidebar-close {
            display: none;
            position: absolute;
            top: 18px;
            right: 15px;
            width: 36px;
            height: 36px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: #D1D5DB;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .sidebar-close:hover {
            background: #C9A227;
            color: #0B0B0C;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 768px) {

            .sidebar {
                position: fixed;
                z-index: 1050;
                top: 0;
                bottom: 0;
                left: -260px;
                width: 250px;
                min-height: 100vh;
                transition: left .3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .sidebar-close {
                display: flex;
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                z-index: 1040;
                background: rgba(0, 0, 0, .45);
                opacity: 0;
                visibility: hidden;
                transition: opacity .3s ease, visibility .3s ease;
            }

            .sidebar-overlay.show {
                display: block;
                opacity: 1;
                visibility: visible;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="d-flex">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="brand">
                    Market<span>Ku</span>
                    <small class="d-block text-white-50 fs-6 fw-normal">Admin Panel</small>
                </div>
                <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Tutup sidebar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
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
                <a href="{{ route('admin.tags.index') }}"
                    class="{{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                    <i class="bi bi-hash"></i> Tags & Kata Terkait
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
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="flex-fill">
            <header class="bg-white shadow-sm d-flex align-items-center justify-content-between px-4 py-3">
                <button type="button" class="btn d-md-none" id="sidebarToggle" aria-label="Buka menu">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                sidebar.classList.add('show');
                sidebarOverlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            sidebarToggle?.addEventListener('click', function() {
                if (sidebar.classList.contains('show')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            sidebarClose?.addEventListener('click', function() {
                closeSidebar();
            });

            sidebarOverlay?.addEventListener('click', function() {
                closeSidebar();
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });

            // Otomatis tutup ketika menu sidebar diklik di mobile
            sidebar.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });

            // Reset state ketika kembali ke desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeSidebar();
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
