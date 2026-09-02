<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Fishery - Digitalisasi Tambak & Koperasi Nelayan Cerdas</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --sf-primary: #0284c7;
            --sf-primary-dark: #0369a1;
            --sf-accent: #06b6d4;
            --sf-navy-dark: #051329;
            --sf-navy-mid: #091e3a;
            --sf-navy-blue: #0c3562;
            --sf-text-dark: #0f172a;
            --sf-text-muted: #475569;
            --sf-border: #e2e8f0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            color: var(--sf-text-dark);
            overflow-x: hidden;
            font-weight: 500;
        }

        /* Modern Sticky Glass Navbar */
        .navbar-custom {
            background: rgba(5, 19, 41, 0.96);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0.95rem 0;
            position: sticky;
            top: 0;
            z-index: 1050;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 1.15rem; /* Jarak lega logo dengan judul */
        }

        .navbar-brand .brand-logo {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #06b6d4 0%, #0284c7 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.35rem;
            box-shadow: 0 4px 14px rgba(6, 182, 212, 0.35);
            flex-shrink: 0;
        }

        .navbar-brand .brand-title {
            font-size: 1.32rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.02em;
            line-height: 1.15;
        }

        .navbar-brand .brand-subtitle {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #38bdf8;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .nav-menu-list {
            gap: 2.25rem;
        }

        .nav-menu-link {
            color: #cbd5e1 !important;
            font-size: 1.02rem;
            font-weight: 700;
            padding: 0.5rem 0.25rem;
            position: relative;
            transition: all 0.25s ease;
        }

        .nav-menu-link:hover, .nav-menu-link.active {
            color: #38bdf8 !important;
            transform: translateY(-1px);
        }

        .nav-menu-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2.5px;
            background: linear-gradient(90deg, #06b6d4, #38bdf8);
            border-radius: 4px;
            transition: all 0.25s ease;
            transform: translateX(-50%);
        }

        .nav-menu-link:hover::after, .nav-menu-link.active::after {
            width: 100%;
        }

        .btn-nav-login {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
            border: 1.5px solid rgba(255, 255, 255, 0.22);
            font-size: 0.98rem;
            font-weight: 800;
            padding: 0.65rem 1.45rem;
            border-radius: 0.85rem;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
        }

        .btn-nav-login:hover {
            background: rgba(255, 255, 255, 0.18);
            color: #38bdf8;
            border-color: #38bdf8;
            transform: translateY(-2px);
        }

        .btn-nav-register {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            color: #ffffff;
            border: none;
            font-size: 0.98rem;
            font-weight: 800;
            padding: 0.65rem 1.55rem;
            border-radius: 0.85rem;
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.4);
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
        }

        .btn-nav-register:hover {
            background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(2, 132, 199, 0.55);
        }

        /* 1 Session = 1 Screen Architecture */
        .section-fullscreen {
            min-height: calc(100vh - 76px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4.5rem 0;
            position: relative;
        }

        /* 1. Hero Section (Oceanic Dark Theme) */
        #hero {
            background: linear-gradient(180deg, #051329 0%, #091e3a 50%, #0c3562 100%);
            color: #ffffff;
            min-height: calc(100vh - 76px);
            padding: 3.5rem 0 4rem;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: rgba(6, 182, 212, 0.15);
            border: 1px solid rgba(6, 182, 212, 0.35);
            backdrop-filter: blur(12px);
            border-radius: 2rem;
            padding: 0.55rem 1.35rem;
            font-size: 0.92rem;
            font-weight: 800;
            color: #38bdf8;
        }

        .hero-title {
            font-size: clamp(2.3rem, 4.4vw, 3.5rem);
            font-weight: 900;
            line-height: 1.2;
            letter-spacing: -0.03em;
            color: #ffffff;
        }

        .hero-desc {
            font-size: 1.15rem;
            line-height: 1.8;
            color: #cbd5e1;
            font-weight: 600;
        }

        .hero-cta-btn-primary {
            background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%);
            color: #051329;
            font-weight: 800;
            font-size: 1rem;
            border-radius: 0.85rem;
            padding: 0.85rem 1.95rem;
            border: none;
            box-shadow: 0 6px 24px rgba(56, 189, 248, 0.35);
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            min-width: 190px;
        }

        .hero-cta-btn-primary:hover {
            background: linear-gradient(135deg, #7dd3fc 0%, #38bdf8 100%);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(56, 189, 248, 0.5);
            color: #051329;
        }

        .hero-cta-btn-outline {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            font-weight: 800;
            font-size: 1rem;
            border-radius: 0.85rem;
            padding: 0.85rem 1.95rem;
            border: 1.5px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            min-width: 190px;
        }

        .hero-cta-btn-outline:hover {
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
            transform: translateY(-3px);
            border-color: #38bdf8;
        }

        .glass-card-hero {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 1.6rem;
            backdrop-filter: blur(16px);
            padding: 2.5rem 2.25rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .hero-stat-box {
            padding: 1.35rem 1.25rem;
            border-radius: 1rem;
            background: rgba(5, 19, 41, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.25s ease;
        }

        .hero-stat-box:hover {
            background: rgba(5, 19, 41, 0.85);
            border-color: rgba(56, 189, 248, 0.3);
        }

        /* 2. White Sections (Fitur, Tentang, Komoditas) */
        #layanan {
            background-color: #ffffff;
        }

        #tentang {
            background-color: #f8fafc;
            border-top: 1px solid var(--sf-border);
            border-bottom: 1px solid var(--sf-border);
        }

        #artikel {
            background-color: #ffffff;
        }

        .white-card {
            background: #ffffff;
            border: 1px solid var(--sf-border);
            border-radius: 1.35rem;
            padding: 2.25rem 2rem;
            box-shadow: 0 8px 24px -4px rgba(15, 23, 42, 0.04);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .white-card:hover {
            transform: translateY(-6px);
            border-color: #93c5fd;
            box-shadow: 0 16px 36px -6px rgba(2, 132, 199, 0.14);
        }

        .pro-icon-box {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.45rem;
            margin-bottom: 1.35rem;
        }

        .section-title-light {
            font-size: clamp(2rem, 3.5vw, 2.75rem);
            font-weight: 900;
            letter-spacing: -0.02em;
            color: var(--sf-text-dark);
            margin-bottom: 0.85rem;
        }

        .section-subtitle-light {
            font-size: 1.1rem;
            color: var(--sf-text-muted);
            font-weight: 600;
            line-height: 1.7;
        }

        /* Staggered Component Entrance Animations */
        .reveal-section {
            opacity: 0;
            transform: translateY(35px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-section.active {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-item {
            opacity: 0;
            transform: translateY(30px) scale(0.97);
            transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-section.active .reveal-item {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .reveal-section.active .reveal-item:nth-child(1) { transition-delay: 0.1s; }
        .reveal-section.active .reveal-item:nth-child(2) { transition-delay: 0.2s; }
        .reveal-section.active .reveal-item:nth-child(3) { transition-delay: 0.3s; }
        .reveal-section.active .reveal-item:nth-child(4) { transition-delay: 0.4s; }

        /* Footer */
        footer {
            background: #040e1f;
            color: #94a3b8;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 3.5rem 0 2.5rem;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .section-fullscreen {
                min-height: auto;
                padding: 4.5rem 0;
            }
            .nav-menu-list {
                gap: 1rem;
                padding: 1rem 0;
            }
        }
    </style>
</head>
<body>

    <!-- Sticky Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand text-decoration-none" href="{{ route('home') }}">
                <div class="brand-logo">
                    <i class="bi bi-water"></i>
                </div>
                <div>
                    <div class="brand-title">Smart Fishery</div>
                    <div class="brand-subtitle">Desa Nelayan Cerdas</div>
                </div>
            </a>

            <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <i class="bi bi-list fs-2 text-white"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 nav-menu-list align-items-center">
                    <li class="nav-item"><a class="nav-link nav-menu-link active" href="#hero">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link nav-menu-link" href="#layanan">Fitur Unggulan</a></li>
                    <li class="nav-item"><a class="nav-link nav-menu-link" href="#tentang">Tentang Sistem</a></li>
                    <li class="nav-item"><a class="nav-link nav-menu-link" href="#artikel">Komoditas</a></li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    @auth
                        @if(Auth::user()->role === 'petambak')
                            <a href="{{ route('petambak.dashboard') }}" class="btn-nav-register">
                                <i class="bi bi-speedometer2"></i> Dashboard Petambak
                            </a>
                        @else
                            <a href="{{ route('kud.dashboard') }}" class="btn-nav-register">
                                <i class="bi bi-building"></i> Dashboard KUD
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-nav-login">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn-nav-register">
                            <i class="bi bi-person-plus-fill"></i> Daftar Akun
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Section 1: Hero (Oceanic Dark Theme) -->
    <section id="hero" class="section-fullscreen reveal-section active">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="hero-badge mb-3.5">
                        <i class="bi bi-shield-check"></i> Inovasi Sistem Informasi Nelayan Cerdas
                    </div>
                    <h1 class="hero-title mb-3.5">
                        Optimalkan Panen Tambak dengan <span style="color: #38bdf8;">Data Cerdas</span>
                    </h1>
                    <p class="hero-desc mb-4">
                        Platform terpadu petambak ikan dan Koperasi Unit Desa (KUD). Pantau kualitas air tambak, estimasikan bobot panen ikan, dan dapatkan transparansi tata kelola pasca panen.
                    </p>

                    <div class="d-flex flex-wrap gap-3 mb-2">
                        <a href="{{ route('login') }}" class="hero-cta-btn-primary">
                            <i class="bi bi-door-open-fill"></i> Masuk ke Portal
                        </a>
                        <a href="{{ route('register') }}" class="hero-cta-btn-outline">
                            <i class="bi bi-person-plus-fill"></i> Daftar Akun Baru
                        </a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="glass-card-hero">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="fw-bold text-white fs-6">
                                <i class="bi bi-cpu-fill text-info me-1.5"></i> Ringkasan Sistem Cerdas
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold">
                                Python Server Ready
                            </span>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="hero-stat-box">
                                    <div class="text-white-50 small fw-bold mb-2">Akurasi Naïve Bayes</div>
                                    <div class="h3 fw-bold text-info mb-1">98,15%</div>
                                    <small class="text-secondary fw-semibold">Uji 54 Data Testing</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="hero-stat-box">
                                    <div class="text-white-50 small fw-bold mb-2">Nilai MAPE Regresi</div>
                                    <div class="h3 fw-bold text-success mb-1">&lt; 3,5%</div>
                                    <small class="text-secondary fw-semibold">Tingkat Error Rendah</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="hero-stat-box d-flex justify-content-between align-items-center py-3.5 px-3.5">
                                    <div>
                                        <div class="text-white-50 small fw-bold mb-1">Komoditas Didukung:</div>
                                        <div class="fw-bold text-white fs-6">Ikan Bandeng, Udang Vaname, Udang Windu</div>
                                    </div>
                                    <i class="bi bi-water text-info fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2: Fitur Unggulan (Pure White Theme) -->
    <section id="layanan" class="section-fullscreen reveal-section">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-5">
                <h2 class="section-title-light">Fitur Unggulan Smart Fishery</h2>
                <p class="section-subtitle-light">Kemudahan akses pemantauan dan pengelolaan budidaya dalam satu ekosistem terpadu.</p>
            </div>

            <div class="row g-4">
                <!-- Fitur 1 -->
                <div class="col-md-6 col-lg-3 reveal-item">
                    <div class="white-card">
                        <div>
                            <div class="pro-icon-box bg-primary-subtle text-primary">
                                <i class="bi bi-droplet-half"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2.5">Cek Kualitas Air</h4>
                            <p class="text-muted fw-semibold small mb-4" style="line-height: 1.7;">
                                Klasifikasi kondisi air secara presisi (Normal vs Tidak Normal) berdasarkan data suhu dan pH air.
                            </p>
                        </div>
                        <div class="pt-3 border-top text-primary small fw-bold d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-right-circle"></i> Gaussian Naïve Bayes
                        </div>
                    </div>
                </div>

                <!-- Fitur 2 -->
                <div class="col-md-6 col-lg-3 reveal-item">
                    <div class="white-card">
                        <div>
                            <div class="pro-icon-box bg-success-subtle text-success">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2.5">Prediksi Panen</h4>
                            <p class="text-muted fw-semibold small mb-4" style="line-height: 1.7;">
                                Estimasi bobot total panen (Kg) sesuai siklus bulan budidaya dengan model proyeksi matematis terukur.
                            </p>
                        </div>
                        <div class="pt-3 border-top text-success small fw-bold d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-right-circle"></i> Regresi Linier & SR
                        </div>
                    </div>
                </div>

                <!-- Fitur 3 -->
                <div class="col-md-6 col-lg-3 reveal-item">
                    <div class="white-card">
                        <div>
                            <div class="pro-icon-box bg-info-subtle text-info-emphasis">
                                <i class="bi bi-building"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2.5">Integrasi KUD</h4>
                            <p class="text-muted fw-semibold small mb-4" style="line-height: 1.7;">
                                Transparansi pembaruan harga pasar komoditas ikan dan rekapitulasi data penjualan panen petambak.
                            </p>
                        </div>
                        <div class="pt-3 border-top text-info-emphasis small fw-bold d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-right-circle"></i> Multi-Role Koperasi
                        </div>
                    </div>
                </div>

                <!-- Fitur 4 -->
                <div class="col-md-6 col-lg-3 reveal-item">
                    <div class="white-card">
                        <div>
                            <div class="pro-icon-box bg-secondary-subtle text-secondary">
                                <i class="bi bi-database-check"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2.5">Database Terpusat</h4>
                            <p class="text-muted fw-semibold small mb-4" style="line-height: 1.7;">
                                Penyimpanan terstruktur data latih dan histori hasil pengujian air untuk kesinambungan siklus panen.
                            </p>
                        </div>
                        <div class="pt-3 border-top text-secondary small fw-bold d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-right-circle"></i> Manajemen Data Latih
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Tentang Sistem (Soft Slate White Theme) -->
    <section id="tentang" class="section-fullscreen reveal-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 reveal-item">
                    <div class="white-card p-4 p-md-5">
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div class="rounded-4 bg-primary text-white p-3.5 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 60px; height: 60px;">
                                <i class="bi bi-award fs-2"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold text-dark mb-1">Riset Terapan PENS</h4>
                                <small class="text-primary fw-bold fs-6">Politeknik Elektronika Negeri Surabaya</small>
                            </div>
                        </div>
                        <p class="text-muted fw-semibold mb-4" style="line-height: 1.8; font-size: 1.02rem;">
                            Sistem ini dirancang untuk menjawab tantangan petambak dalam mengantisipasi risiko penurunan mutu air dan mengoptimalkan tata kelola pasca panen di desa nelayan secara terpadu.
                        </p>
                        <div class="row g-3 fw-bold text-secondary small">
                            <div class="col-6"><i class="bi bi-check-circle-fill text-primary me-1.5 fs-6"></i> Standarisasi Padat Tebar</div>
                            <div class="col-6"><i class="bi bi-check-circle-fill text-primary me-1.5 fs-6"></i> Survival Rate (SR)</div>
                            <div class="col-6"><i class="bi bi-check-circle-fill text-primary me-1.5 fs-6"></i> Monitoring Multi-Kolam</div>
                            <div class="col-6"><i class="bi bi-check-circle-fill text-primary me-1.5 fs-6"></i> Tata Kelola Pasca Panen</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 reveal-item">
                    <h2 class="section-title-light mb-3.5">Solusi Terpadu Menuju Kemakmuran Petambak</h2>
                    <p class="section-subtitle-light mb-4" style="line-height: 1.8;">
                        Dengan menghubungkan langsung petambak dengan KUD secara digital, proses transaksi hasil panen dan perkiraan pendapatan menjadi lebih akurat, transparan, dan dapat diandalkan.
                    </p>
                    <a href="{{ route('register') }}" class="btn btn-primary rounded-3 px-4 py-3 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                        Mulai Registrasi Akun <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 4: Komoditas & Panduan (Pure White Theme) -->
    <section id="artikel" class="section-fullscreen reveal-section">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-5">
                <h2 class="section-title-light">Standar Parameter Kualitas Air</h2>
                <p class="section-subtitle-light">Rentang nilai suhu dan pH ideal budidaya berdasarkan acuan baku mutu KKP.</p>
            </div>

            <div class="row g-4">
                <!-- Komoditas 1 -->
                <div class="col-md-4 reveal-item">
                    <div class="white-card text-center p-4">
                        <div class="rounded-circle bg-primary-subtle text-primary mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="bi bi-water fs-2"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Ikan Bandeng</h4>
                        <p class="text-primary fw-bold small mb-3">Siklus: 10 - 12 Bulan</p>
                        <div class="p-3 bg-light rounded-3 text-start small fw-semibold border">
                            <div class="d-flex justify-content-between mb-1.5 text-muted">
                                <span>Suhu Normal:</span>
                                <strong class="text-dark">28 – 32 °C</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1.5 text-muted">
                                <span>pH Normal:</span>
                                <strong class="text-dark">7.0 – 8.5</strong>
                            </div>
                            <div class="d-flex justify-content-between text-muted">
                                <span>Padat Tebar:</span>
                                <strong class="text-dark">1.500 ekor / Ha</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Komoditas 2 -->
                <div class="col-md-4 reveal-item">
                    <div class="white-card text-center p-4">
                        <div class="rounded-circle bg-info-subtle text-info mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="bi bi-tsunami fs-2"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Udang Vaname</h4>
                        <p class="text-info fw-bold small mb-3">Siklus: 3 - 4 Bulan</p>
                        <div class="p-3 bg-light rounded-3 text-start small fw-semibold border">
                            <div class="d-flex justify-content-between mb-1.5 text-muted">
                                <span>Suhu Normal:</span>
                                <strong class="text-dark">28 – 31 °C</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1.5 text-muted">
                                <span>pH Normal:</span>
                                <strong class="text-dark">7.5 – 8.3</strong>
                            </div>
                            <div class="d-flex justify-content-between text-muted">
                                <span>Padat Tebar:</span>
                                <strong class="text-dark">100 – 150 ekor / m²</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Komoditas 3 -->
                <div class="col-md-4 reveal-item">
                    <div class="white-card text-center p-4">
                        <div class="rounded-circle bg-success-subtle text-success mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="bi bi-shield-check fs-2"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Udang Windu</h4>
                        <p class="text-success fw-bold small mb-3">Siklus: 5 - 6 Bulan</p>
                        <div class="p-3 bg-light rounded-3 text-start small fw-semibold border">
                            <div class="d-flex justify-content-between mb-1.5 text-muted">
                                <span>Suhu Normal:</span>
                                <strong class="text-dark">26 – 29 °C</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1.5 text-muted">
                                <span>pH Normal:</span>
                                <strong class="text-dark">7.8 – 8.5</strong>
                            </div>
                            <div class="d-flex justify-content-between text-muted">
                                <span>Padat Tebar:</span>
                                <strong class="text-dark">25 – 35 ekor / m²</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container text-center small">
            <div class="mb-2 text-white fw-bold fs-6">
                Smart Fishery
            </div>
            <div class="text-white-50">
                Sistem Informasi Komunikasi & Tata Kelola Pertambakan Desa Nelayan Cerdas
            </div>
            <div class="mt-2 text-white-50">&copy; {{ date('Y') }} Politeknik Elektronika Negeri Surabaya (PENS). All Rights Reserved.</div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Staggered Component Reveal on Swap / Scroll Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observerOptions = {
                threshold: 0.15,
                rootMargin: '0px 0px -40px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal-section').forEach(section => {
                observer.observe(section);
            });

            // Active Link Tracker on Scroll
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-menu-link');

            window.addEventListener('scroll', () => {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop - 100;
                    const sectionHeight = section.clientHeight;
                    if (pageYOffset >= sectionTop && pageYOffset < sectionTop + sectionHeight) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === '#' + current) {
                        link.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
