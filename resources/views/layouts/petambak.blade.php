<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Petambak') - Smart Fishery Village</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Animate.css for SweetAlert animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sf-primary: #0284c7;
            --sf-primary-dark: #0369a1;
            --sf-secondary: #0f172a;
            --sf-accent: #06b6d4;
            --sf-bg: #f8fafc;
            --sf-card-bg: #ffffff;
            --sf-border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--sf-bg);
            color: #1e293b;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 270px;
            background: linear-gradient(180deg, #091e3a 0%, #0c2b4e 60%, #06182c 100%);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
        }

        .sidebar-brand {
            padding: 1.6rem 1.4rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #ffffff;
        }

        .sidebar-brand .brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #06b6d4 0%, #0284c7 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(6, 182, 212, 0.35);
        }

        .sidebar-brand .brand-text {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .sidebar-brand .brand-sub {
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .sidebar-section-title {
            padding: 1.25rem 1.4rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0.5rem 0.85rem;
            margin: 0;
            flex-grow: 1;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.8rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 0.75rem;
            font-size: 0.92rem;
            font-weight: 600;
            transition: all 0.2s ease;
            margin-bottom: 0.3rem;
        }

        .nav-item-link i {
            font-size: 1.2rem;
            color: #94a3b8;
            transition: all 0.2s ease;
        }

        .nav-item-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(3px);
        }

        .nav-item-link:hover i {
            color: #38bdf8;
        }

        .nav-item-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, rgba(2, 132, 199, 0.9) 0%, rgba(14, 165, 233, 0.9) 100%);
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.35);
        }

        .nav-item-link.active i {
            color: #ffffff;
        }

        .sidebar-footer {
            padding: 1.1rem 1.4rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.18);
        }

        /* Main Content Wrapper */
        .main-wrapper {
            margin-left: 270px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .top-navbar {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--sf-border);
            padding: 0.75rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .content-area {
            padding: 2rem 2.25rem;
            flex-grow: 1;
        }

        /* Top Navbar User Dropdown */
        .user-dropdown-btn {
            background: #ffffff;
            border: 1px solid var(--sf-border);
            padding: 0.35rem 0.85rem 0.35rem 0.35rem;
            border-radius: 2rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }

        .user-dropdown-btn:hover {
            border-color: #93c5fd;
            background: #f8fafc;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.1);
        }

        .user-avatar-top {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.3);
            flex-shrink: 0;
        }

        /* Cards & Components */
        .card-custom {
            background: #ffffff;
            border: 1px solid var(--sf-border);
            border-radius: 1.15rem;
            box-shadow: 0 4px 20px -4px rgba(15, 23, 42, 0.04);
            transition: all 0.25s ease;
        }

        .card-custom:hover {
            box-shadow: 0 8px 28px -6px rgba(15, 23, 42, 0.08);
        }

        .stat-card {
            border-radius: 1.15rem;
            border: 1px solid var(--sf-border);
            background: #ffffff;
            padding: 1.35rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1.1rem;
            box-shadow: 0 4px 16px -3px rgba(15, 23, 42, 0.04);
            transition: all 0.25s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px -4px rgba(2, 132, 199, 0.12);
            border-color: #93c5fd;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        /* Responsive Table & Typography Enhancements */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 0.85rem;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
            position: relative;
        }

        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .table-responsive table {
            min-width: 680px;
            margin-bottom: 0;
        }

        .table-responsive table th,
        .table-responsive table td {
            vertical-align: middle;
            padding: 0.8rem 0.95rem;
            font-size: 0.88rem;
            white-space: nowrap;
        }

        .table-responsive table th {
            font-weight: 700;
            color: #475569;
            letter-spacing: 0.02em;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        /* Interactive Clickable Table Rows */
        .table-row-clickable {
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .table-row-clickable:hover {
            background-color: #f0fdf4 !important;
            transform: scale(1.002);
        }

        .table-row-clickable:active {
            background-color: #dcfce7 !important;
        }

        /* Popup Detail Modal Styling */
        .modal-detail-dialog {
            max-width: 820px;
        }

        .modal-detail-content {
            border-radius: 1.25rem !important;
            border: none;
            overflow: hidden;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.25);
        }

        .modal-detail-header {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            padding: 1.25rem 1.75rem;
            border: none;
        }

        .modal-detail-body {
            background-color: #f8fafc;
            padding: 1.5rem 1.75rem;
        }

        .modal-detail-footer {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 1rem 1.75rem;
        }

        .modal-card-item {
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 8px -2px rgba(15, 23, 42, 0.04);
        }

        .modal-card-item:last-child {
            margin-bottom: 0;
        }

        .param-grid-card {
            background: #ffffff;
            border-radius: 0.85rem;
            border: 1px solid #e2e8f0;
            padding: 1.15rem 1.15rem;
            height: 100%;
            box-shadow: 0 2px 6px -2px rgba(15, 23, 42, 0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .prob-subcard {
            border-radius: 0.85rem;
            padding: 1.15rem 1.25rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            box-shadow: 0 2px 6px -2px rgba(15, 23, 42, 0.03);
        }

        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
            .content-area {
                padding: 1.25rem;
            }
            .top-navbar {
                padding: 0.75rem 1.25rem;
            }
        }

        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1040;
            display: none;
        }

        .sidebar-backdrop.show {
            display: block;
        }
    </style>
</head>
<body>

    <!-- Mobile Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebarMenu">
        <a href="{{ route('petambak.dashboard') }}" class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-water"></i>
            </div>
            <div>
                <div class="brand-text">Smart Fishery</div>
                <div class="brand-sub">Portal Petambak</div>
            </div>
        </a>

        <div class="sidebar-section-title">Menu Utama</div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('petambak.dashboard') }}" class="nav-item-link {{ request()->routeIs('petambak.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('petambak.tambak.index') }}" class="nav-item-link {{ request()->routeIs('petambak.tambak.*') ? 'active' : '' }}">
                    <i class="bi bi-plus-square"></i>
                    <span>Input Data</span>
                </a>
            </li>
            <li>
                <a href="{{ route('petambak.kualitas-air.index') }}" class="nav-item-link {{ request()->routeIs('petambak.kualitas-air.*') ? 'active' : '' }}">
                    <i class="bi bi-droplet-half"></i>
                    <span>Cek Kualitas Air</span>
                </a>
            </li>
            <li>
                <a href="{{ route('petambak.prediksi.index') }}" class="nav-item-link {{ request()->routeIs('petambak.prediksi.*') ? 'active' : '' }}">
                    <i class="bi bi-calculator"></i>
                    <span>Prediksi Panen</span>
                </a>
            </li>
            <li>
                <a href="{{ route('petambak.dataset.index') }}" class="nav-item-link {{ request()->routeIs('petambak.dataset.*') ? 'active' : '' }}">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <span>Tambah Sumber Data</span>
                </a>
            </li>
            <li>
                <a href="{{ route('petambak.panen.index') }}" class="nav-item-link {{ request()->routeIs('petambak.panen.*') ? 'active' : '' }}">
                    <i class="bi bi-info-square"></i>
                    <span>Informasi</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer text-center">
            <div class="d-flex align-items-center justify-content-center gap-2 small text-white-50">
                <span class="spinner-grow spinner-grow-sm text-info" style="width: 6px; height: 6px;" role="status"></span>
                <span>Smart Fishery PENS</span>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar with User Profile Dropdown -->
        <header class="top-navbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2.5">
                <button class="btn btn-light d-lg-none p-1.5 rounded-2" onclick="toggleSidebar()" aria-label="Toggle Menu">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="fw-bold text-dark fs-6 d-none d-sm-block">
                    Portal Nelayan Cerdas
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-md-flex align-items-center gap-2">
                    <i class="bi bi-calendar3 text-primary"></i>
                    <span class="text-secondary small fw-semibold">{{ date('d F Y') }}</span>
                </div>

                <!-- User Profile Dropdown Menu -->
                <div class="dropdown">
                    <button class="user-dropdown-btn border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar-top">
                            {{ strtoupper(substr(Auth::user()->username ?? 'P', 0, 1)) }}
                        </div>
                        <div class="text-start d-none d-sm-block">
                            <div class="fw-bold text-dark lh-1 small">{{ Auth::user()->username ?? 'Petambak' }}</div>
                            <small class="text-primary fw-bold" style="font-size: 0.72rem;">Petambak</small>
                        </div>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2 mt-2" style="min-width: 220px;">
                        <li class="px-3 py-2 border-bottom mb-1">
                            <div class="fw-bold text-dark small">{{ Auth::user()->username }}</div>
                            <div class="text-muted small text-truncate" style="font-size: 0.75rem;">{{ Auth::user()->email }}</div>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-2 d-flex align-items-center gap-2 py-2 small fw-semibold" href="{{ route('petambak.profile.edit') }}">
                                <i class="bi bi-person-gear text-primary fs-6"></i>
                                <span>Edit Profil</span>
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0" id="logoutForm">
                                @csrf
                                <button type="button" onclick="confirmLogout(event)" class="dropdown-item rounded-2 d-flex align-items-center gap-2 py-2 small fw-semibold text-danger">
                                    <i class="bi bi-box-arrow-right fs-6"></i>
                                    <span>Keluar / Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="content-area" id="mainContentArea">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebarMenu').classList.toggle('show');
            document.getElementById('sidebarBackdrop').classList.toggle('show');
        }

        // Konfigurasi SweetAlert2 Custom Theme
        const SFToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });

        // Flash Messages dengan Animasi JS
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#0284c7',
                timer: 3500,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__zoomIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut'
                }
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Perhatian!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444',
                showClass: {
                    popup: 'animate__animated animate__shakeX'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut'
                }
            });
        @endif

        // Global Confirmation Handler dengan Animasi JS
        function confirmAction(options = {}) {
            return Swal.fire({
                title: options.title || 'Apakah Anda Yakin?',
                text: options.text || 'Tindakan ini tidak dapat dibatalkan.',
                icon: options.icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: options.confirmButtonColor || '#0284c7',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: options.confirmButtonText || '<i class="bi bi-check2"></i> Ya, Lanjutkan',
                cancelButtonText: '<i class="bi bi-x"></i> Batal',
                reverseButtons: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        }

        // Intercept Logout Confirmation
        function confirmLogout(event) {
            event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Keluar dari Sistem?',
                text: 'Sesi akun petambak Anda akan diakhiri.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="bi bi-box-arrow-right"></i> Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                showClass: {
                    popup: 'animate__animated animate__zoomIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    <!-- Smart Fishery Live Engine -->
    <script src="{{ asset('assets/js/smart-live-engine.js') }}"></script>
    @yield('scripts')
</body>
</html>
