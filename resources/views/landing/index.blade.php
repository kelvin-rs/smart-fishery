<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Fishery - Digitalisasi Tambak & Koperasi Nelayan</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .top-subbar {
            background-color: #0f172a;
            color: #94a3b8;
            font-size: 0.825rem;
        }

        .hero-section {
            background: linear-gradient(145deg, #0c4a6e 0%, #0369a1 50%, #0284c7 100%);
            color: #ffffff;
            padding: 5rem 0 6.5rem;
            position: relative;
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(8px);
        }

        .hero-stats-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1.25rem;
            backdrop-filter: blur(12px);
        }

        .stat-item {
            background: rgba(15, 23, 42, 0.25);
            border-radius: 0.85rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1.25rem;
        }

        .card-feature {
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            background: #ffffff;
            transition: all 0.25s ease-in-out;
        }

        .card-feature:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -6px rgba(15, 23, 42, 0.08);
            border-color: #cbd5e1;
        }

        .feature-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        .btn-custom-primary {
            background-color: #ffffff;
            color: #0369a1;
            font-weight: 700;
            border-radius: 0.65rem;
            padding: 0.75rem 1.75rem;
            border: none;
            transition: all 0.2s;
        }

        .btn-custom-primary:hover {
            background-color: #f0f9ff;
            color: #0c4a6e;
            transform: translateY(-2px);
        }

        .btn-custom-outline {
            background-color: transparent;
            color: #ffffff;
            border: 1.5px solid rgba(255, 255, 255, 0.6);
            font-weight: 600;
            border-radius: 0.65rem;
            padding: 0.75rem 1.75rem;
            transition: all 0.2s;
        }

        .btn-custom-outline:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border-color: #ffffff;
        }
    </style>
</head>
<body>

    <!-- Top Sub-Bar -->
    <div class="top-subbar py-2 px-3">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-3">
                <span><i class="bi bi-envelope me-1"></i> info@smartfishery.id</span>
                <span><i class="bi bi-telephone me-1"></i> +62 856 3260 020</span>
            </div>
            <div>
                <span class="opacity-75">Sistem Riset & Komunikasi Desa Nelayan - PENS</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center gap-2 fs-4" href="{{ route('home') }}">
                <i class="bi bi-water text-info fs-3"></i> Smart Fishery
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                    <li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#layanan">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link text-secondary fw-semibold" href="#komoditas">Komoditas</a></li>
                    @auth
                        @if (Auth::user()->isKud())
                            <li class="nav-item ms-lg-2">
                                <a href="{{ route('kud.dashboard') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold">
                                    <i class="bi bi-grid-fill me-1"></i> Dashboard KUD
                                </a>
                            </li>
                        @else
                            <li class="nav-item ms-lg-2">
                                <a href="{{ route('petambak.dashboard') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold">
                                    <i class="bi bi-grid-fill me-1"></i> Dashboard Petambak
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item ms-lg-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary px-3 py-2 rounded-3 fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-primary px-3 py-2 rounded-3 fw-semibold">
                                <i class="bi bi-person-plus me-1"></i> Daftar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill hero-badge small fw-semibold mb-3">
                        <i class="bi bi-check-circle-fill text-warning"></i> Inovasi Pertambakan Cerdas 4.0
                    </div>
                    <h1 class="display-5 fw-bold lh-sm mb-3">
                        Digitalisasi Hasil Panen & Pengelolaan Tambak
                    </h1>
                    <p class="lead opacity-90 mb-4 fw-normal lh-base" style="font-size: 1.1rem; color: #e0f2fe;">
                        Membantu petani tambak memantau suhu dan pH air, mengklasifikasi kesehatan tambak dengan metode Naïve Bayes, serta memprediksi potensi hasil panen terintegrasi Koperasi Unit Desa (KUD).
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-custom-primary">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Buka Dashboard
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-custom-outline">
                                <i class="bi bi-person-plus me-1"></i> Registrasi Akun
                            </a>
                        @else
                            @if (Auth::user()->isKud())
                                <a href="{{ route('kud.dashboard') }}" class="btn btn-custom-primary">
                                    <i class="bi bi-shop me-1"></i> Masuk Dashboard KUD
                                </a>
                            @else
                                <a href="{{ route('petambak.dashboard') }}" class="btn btn-custom-primary">
                                    <i class="bi bi-speedometer2 me-1"></i> Masuk Dashboard Petambak
                                </a>
                            @endif
                        @endguest
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="hero-stats-card p-4">
                        <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom border-white border-opacity-25">
                            <i class="bi bi-sliders text-info fs-5"></i>
                            <h5 class="mb-0 fw-bold">Parameter Utama Sistem</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="h3 fw-bold text-info mb-1">98.15%</div>
                                    <div class="small opacity-75">Akurasi Naive Bayes</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="h3 fw-bold text-info mb-1">3 Komoditas</div>
                                    <div class="small opacity-75">Bandeng, Vaname, Windu</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="h3 fw-bold text-info mb-1">3 Waktu</div>
                                    <div class="small opacity-75">Pagi, Siang, Sore</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="h3 fw-bold text-info mb-1">KUD Sync</div>
                                    <div class="small opacity-75">Update Harga & Panen</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Services / Feature Grid -->
    <section class="py-5" id="layanan">
        <div class="container py-4">
            <div class="text-center max-w-700 mx-auto mb-5">
                <h2 class="fw-bold">Fitur Utama Sistem Smart Fishery</h2>
                <p class="text-secondary">Solusi menyeluruh dari pencatatan kondisi air harian hingga rekap penjualan hasil panen.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card card-feature h-100 p-4 shadow-sm">
                        <div class="feature-icon-box bg-primary-subtle text-primary mb-3">
                            <i class="bi bi-thermometer-half"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Monitoring Air</h5>
                        <p class="text-muted small mb-0">Pencatatan suhu dan pH air tambak tiga kali sehari untuk memantau stabilitas lingkungan kolam.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card card-feature h-100 p-4 shadow-sm">
                        <div class="feature-icon-box bg-info-subtle text-info mb-3">
                            <i class="bi bi-cpu"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Uji Naïve Bayes</h5>
                        <p class="text-muted small mb-0">Klasifikasi status tambak (Normal / Tidak Normal) berbasis Gaussian Naïve Bayes secara objektif.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card card-feature h-100 p-4 shadow-sm">
                        <div class="feature-icon-box bg-success-subtle text-success mb-3">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Prediksi Panen</h5>
                        <p class="text-muted small mb-0">Estimasi bobot panen (Kg) menggunakan metode Regresi Linier dan faktor kelangsungan hidup (SR).</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card card-feature h-100 p-4 shadow-sm">
                        <div class="feature-icon-box bg-warning-subtle text-warning mb-3">
                            <i class="bi bi-shop"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Integrasi KUD</h5>
                        <p class="text-muted small mb-0">Informasi harga pasar terpusat dan rekapitulasi data timbangan panen petambak secara transparan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-5">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 small text-muted">
            <div>
                &copy; {{ date('Y') }} <strong>Smart Fishery</strong> - Program Studi Teknik Telekomunikasi PENS.
            </div>
            <div>
                <span>Digitalisasi Desa Nelayan Cerdas Berbasis Web</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
