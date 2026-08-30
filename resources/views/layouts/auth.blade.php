<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi') - Smart Fishery</title>
    
    <!-- Google Fonts (Plus Jakarta Sans) -->
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
            background-color: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .auth-wrapper {
            width: 100%;
            max-width: 960px;
            margin: auto;
        }

        .auth-card {
            border-radius: 1.25rem;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            background: #ffffff;
        }

        .auth-side-banner {
            background: linear-gradient(160deg, #0c4a6e 0%, #0369a1 60%, #0284c7 100%);
            color: #ffffff;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .form-control, .form-select {
            border-radius: 0.65rem;
            padding: 0.7rem 1rem;
            border-color: #cbd5e1;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 0.25rem rgba(2, 132, 199, 0.15);
        }

        .btn-primary-custom {
            background-color: #0284c7;
            border-color: #0284c7;
            color: #ffffff;
            border-radius: 0.65rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-primary-custom:hover {
            background-color: #0369a1;
            border-color: #0369a1;
            color: #ffffff;
        }

        .input-group-text {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            border-top-left-radius: 0.65rem;
            border-bottom-left-radius: 0.65rem;
            color: #64748b;
        }
        
        .input-group .form-control:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group .form-control:last-child {
            border-top-right-radius: 0.65rem;
            border-bottom-right-radius: 0.65rem;
        }

        .btn-toggle-password {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            color: #64748b;
            border-top-right-radius: 0.65rem !important;
            border-bottom-right-radius: 0.65rem !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.7rem 0.9rem;
            cursor: pointer;
        }

        .btn-toggle-password:hover, .btn-toggle-password:focus {
            background-color: #e2e8f0;
            border-color: #cbd5e1;
            color: #0f172a;
            box-shadow: none;
        }

        .auth-feature-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .auth-feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 0.65rem;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.28);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: #ffffff;
            flex-shrink: 0;
            backdrop-filter: blur(4px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="auth-wrapper">
            <div class="card auth-card">
                <div class="row g-0">
                    <!-- Banner Informasi (Kiri) -->
                    <div class="col-lg-5 d-none d-lg-block auth-side-banner">
                        <div>
                            <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-white bg-opacity-20 text-white small fw-semibold mb-4">
                                <i class="bi bi-water"></i> Smart Fishery System
                            </div>
                            <h3 class="fw-bold lh-sm mb-3">Sistem Informasi Pertambakan & KUD</h3>
                            <p class="text-light opacity-75 small lh-base mb-4">
                                Platform digitalisasi pengelolaan tambak ikan bandeng, udang vaname, dan udang windu terintegrasi Koperasi Unit Desa.
                            </p>

                            <div class="d-flex flex-column gap-3 mt-4">
                                <div class="auth-feature-item">
                                    <div class="auth-feature-icon">
                                        <i class="bi bi-droplet-half"></i>
                                    </div>
                                    <span class="small fw-medium">Monitoring Kualitas Air Harian</span>
                                </div>
                                <div class="auth-feature-item">
                                    <div class="auth-feature-icon">
                                        <i class="bi bi-graph-up-arrow"></i>
                                    </div>
                                    <span class="small fw-medium">Prediksi Panen & Klasifikasi Tambak</span>
                                </div>
                                <div class="auth-feature-item">
                                    <div class="auth-feature-icon">
                                        <i class="bi bi-shop"></i>
                                    </div>
                                    <span class="small fw-medium">Integrasi Data Penjualan KUD</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top border-white border-opacity-20 small text-light opacity-75">
                            &copy; {{ date('Y') }} Politeknik Elektronika Negeri Surabaya (PENS)
                        </div>
                    </div>

                    <!-- Form Content (Kanan) -->
                    <div class="col-lg-7 p-4 p-md-5 d-flex flex-column justify-content-center">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-3 small py-2.5" role="alert">
                                <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2.5" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-3 small text-muted">
                <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left"></i> Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
