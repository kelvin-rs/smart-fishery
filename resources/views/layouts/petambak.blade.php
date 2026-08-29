<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Petambak') - Smart Fishery Village</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            color: #0c4a6e;
            font-weight: 800;
            font-size: 1.25rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem 0.75rem;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex-grow: 1;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.65rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }

        .nav-link-custom:hover {
            background-color: #f8fafc;
            color: #0284c7;
        }

        .nav-link-custom.active {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .nav-link-custom i {
            font-size: 1.15rem;
        }

        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.9rem 2rem;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .content-area {
            padding: 2rem;
            flex-grow: 1;
        }

        .card-custom {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        }

        .stat-card {
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Petambak -->
    <aside class="sidebar" id="sidebarMenu">
        <a href="{{ route('petambak.dashboard') }}" class="sidebar-brand">
            <i class="bi bi-water text-info fs-3"></i>
            <span>Smart Fishery</span>
        </a>

        <div class="px-3 pt-3 pb-1 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">
            Menu Petambak
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('petambak.dashboard') }}" class="nav-link-custom {{ request()->routeIs('petambak.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('petambak.tambak.index') }}" class="nav-link-custom {{ request()->routeIs('petambak.tambak.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i> Data Tambak
                </a>
            </li>
            <li>
                <a href="{{ route('petambak.kualitas-air.index') }}" class="nav-link-custom {{ request()->routeIs('petambak.kualitas-air.*') ? 'active' : '' }}">
                    <i class="bi bi-droplet-half"></i> Cek Kualitas Air
                </a>
            </li>
            <li>
                <a href="{{ route('petambak.prediksi.index') }}" class="nav-link-custom {{ request()->routeIs('petambak.prediksi.*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i> Prediksi Panen
                </a>
            </li>
            <li>
                <a href="{{ route('petambak.panen.index') }}" class="nav-link-custom {{ request()->routeIs('petambak.panen.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-data"></i> Informasi Hasil Panen
                </a>
            </li>
            <li>
                <a href="{{ route('petambak.dataset.index') }}" class="nav-link-custom {{ request()->routeIs('petambak.dataset.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Tambah Sumber Data
                </a>
            </li>
        </ul>

        <div class="p-3 border-top border-light-subtle">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="rounded-circle bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="fw-bold small text-truncate">{{ Auth::user()->username ?? 'Petambak' }}</div>
                    <div class="text-muted small" style="font-size: 0.75rem;">Petambak</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-semibold rounded-2">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar d-flex justify-content-between align-items-center">
            <button class="btn btn-light d-lg-none" type="button" onclick="document.getElementById('sidebarMenu').classList.toggle('show')">
                <i class="bi bi-list fs-4"></i>
            </button>
            
            <div class="d-flex align-items-center gap-2">
                <span class="badge text-bg-primary-subtle text-primary px-3 py-1.5 rounded-pill small fw-semibold">
                    <i class="bi bi-house-door me-1"></i> Smart Fishery Village
                </span>
                <span class="text-muted small d-none d-sm-inline">| Desa Nelayan Cerdas</span>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="text-secondary small fw-medium">
                    <i class="bi bi-calendar3 me-1"></i> {{ date('d M Y') }}
                </span>
            </div>
        </header>

        <!-- Main Content -->
        <main class="content-area">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 small py-2.5 mb-4 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2.5 mb-4 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
