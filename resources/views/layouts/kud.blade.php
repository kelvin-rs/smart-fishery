<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard KUD') - Smart Fishery Village</title>
    
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

    <style>
        :root {
            --sf-primary: #4f46e5;
            --sf-primary-dark: #3730a3;
            --sf-bg: #f8fafc;
            --sf-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--sf-bg);
            color: #1e293b;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            width: 270px;
            background: linear-gradient(180deg, #1e1b4b 0%, #172554 60%, #0f172a 100%);
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
            background: linear-gradient(135deg, #818cf8 0%, #4f46e5 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
        }

        .sidebar-brand .brand-text {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .sidebar-brand .brand-sub {
            font-size: 0.72rem;
            color: #a5b4fc;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .sidebar-section-title {
            padding: 1.25rem 1.4rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0.5rem 0.85rem;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex-grow: 1;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.8rem 1rem;
            border-radius: 0.75rem;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-link-custom i {
            font-size: 1.2rem;
            color: #a5b4fc;
            transition: all 0.2s;
        }

        .nav-link-custom:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            transform: translateX(3px);
        }

        .nav-link-custom.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.9) 0%, rgba(79, 70, 229, 0.9) 100%);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .nav-link-custom.active i {
            color: #ffffff;
        }

        .sidebar-footer {
            padding: 1.1rem 1.4rem;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

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

        /* User Dropdown */
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
            border-color: #a5b4fc;
            background: #f8fafc;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
        }

        .user-avatar-top {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
            flex-shrink: 0;
        }

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

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1040;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .sidebar-backdrop.show {
                display: block;
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
    </style>
    @yield('styles')
</head>
<body>

    <!-- Mobile Sidebar Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    <!-- Sidebar KUD -->
    <aside class="sidebar" id="sidebarMenu">
        <a href="{{ route('kud.dashboard') }}" class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <div class="brand-text">Smart KUD</div>
                <div class="brand-sub">Portal Koperasi</div>
            </div>
        </a>

        <div class="sidebar-section-title">Menu Koperasi</div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('kud.dashboard') }}" class="nav-link-custom {{ request()->routeIs('kud.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard KUD</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kud.harga.index') }}" class="nav-link-custom {{ request()->routeIs('kud.harga.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i>
                    <span>Update Harga Ikan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kud.panen.index') }}" class="nav-link-custom {{ request()->routeIs('kud.panen.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Rekap Hasil Panen</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer text-center">
            <div class="d-flex align-items-center justify-content-center gap-2 small text-white-50">
                <span class="spinner-grow spinner-grow-sm text-info" style="width: 6px; height: 6px;" role="status"></span>
                <span>Smart KUD</span>
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary btn-sm d-lg-none rounded-3 px-2.5 py-1.5" type="button" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-5"></i>
                </button>
                
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-indigo-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill small fw-bold" style="background: #eef2ff;">
                        <i class="bi bi-building me-1"></i> Koperasi Unit Desa (KUD)
                    </span>
                    <span class="text-muted small d-none d-md-inline">Sistem Informasi Manajemen Pasca Panen</span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-sm-flex align-items-center gap-2 bg-light px-3 py-1.5 rounded-pill border">
                    <i class="bi bi-calendar3 text-primary small"></i>
                    <span class="text-secondary small fw-semibold">{{ date('d F Y') }}</span>
                </div>

                <!-- User Dropdown Menu -->
                <div class="dropdown">
                    <button class="user-dropdown-btn border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar-top">
                            {{ strtoupper(substr(Auth::user()->username ?? 'K', 0, 1)) }}
                        </div>
                        <div class="text-start d-none d-sm-block">
                            <div class="fw-bold text-dark lh-1 small">{{ Auth::user()->username ?? 'Admin KUD' }}</div>
                            <small class="text-primary fw-bold" style="font-size: 0.72rem;">Pengurus KUD</small>
                        </div>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2 mt-2" style="min-width: 220px;">
                        <li class="px-3 py-2 border-bottom mb-1">
                            <div class="fw-bold text-dark small">{{ Auth::user()->username }}</div>
                            <div class="text-muted small text-truncate" style="font-size: 0.75rem;">{{ Auth::user()->email }}</div>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-2 d-flex align-items-center gap-2 py-2 small fw-semibold" href="{{ route('kud.profile.edit') }}">
                                <i class="bi bi-person-gear text-primary fs-6"></i>
                                <span>Edit Profil</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1 text-muted opacity-25"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0" id="logoutFormKud">
                                @csrf
                                <button type="button" onclick="confirmLogoutKud(event)" class="dropdown-item rounded-2 d-flex align-items-center gap-2 py-2 small fw-semibold text-danger">
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

        // Flash Messages dengan Animasi JS untuk KUD
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#4f46e5',
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

        // Global Confirmation Handler dengan Animasi JS untuk KUD
        function confirmActionKud(options = {}) {
            return Swal.fire({
                title: options.title || 'Apakah Anda Yakin?',
                text: options.text || 'Tindakan ini akan disimpan ke sistem koperasi.',
                icon: options.icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: options.confirmButtonColor || '#4f46e5',
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

        // Intercept Logout Confirmation KUD
        function confirmLogoutKud(event) {
            event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Keluar dari Portal KUD?',
                text: 'Sesi akun pengurus KUD Anda akan diakhiri.',
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
