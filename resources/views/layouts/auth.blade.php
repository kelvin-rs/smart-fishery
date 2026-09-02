<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi') - Smart Fishery Village</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --sf-primary: #0284c7;
            --sf-primary-dark: #0369a1;
            --sf-accent: #06b6d4;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #091e3a 0%, #0c2b4e 50%, #0369a1 100%);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glow Background Effect */
        body::before {
            content: '';
            position: absolute;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.25) 0%, rgba(6, 182, 212, 0) 70%);
            top: 10%;
            left: 15%;
            z-index: 0;
            filter: blur(40px);
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(99, 102, 241, 0) 70%);
            bottom: 10%;
            right: 15%;
            z-index: 0;
            filter: blur(40px);
            pointer-events: none;
        }
        
        .auth-container {
            width: 100%;
            max-width: 1000px;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            background: #ffffff;
        }

        .auth-side-banner {
            background: linear-gradient(150deg, #0c2b4e 0%, #0369a1 60%, #0284c7 100%);
            color: #ffffff;
            padding: 3.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .auth-side-banner::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.7;
            pointer-events: none;
        }

        .auth-form-side {
            padding: 3.5rem 3rem;
            background: #ffffff;
        }

        .brand-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 2rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: #ffffff;
        }

        .form-control, .form-select {
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            border-color: #e2e8f0;
            font-size: 0.95rem;
            background-color: #f8fafc;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0284c7;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.12);
        }

        .input-group-text {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
            color: #64748b;
            padding-left: 1rem;
            padding-right: 0.85rem;
        }

        .btn-toggle-password {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            color: #64748b;
            border-top-right-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            border: none;
            color: #ffffff;
            border-radius: 0.75rem;
            padding: 0.85rem 1.5rem;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);
            transition: all 0.25s ease;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #0369a1 0%, #0c4a6e 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.4);
            color: #ffffff;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .feature-item i {
            font-size: 1.15rem;
            color: #38bdf8;
        }

        @media (max-width: 768px) {
            .auth-form-side {
                padding: 2.25rem 1.5rem;
            }
            .auth-side-banner {
                padding: 2.25rem 1.5rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <div class="auth-container">
        <div class="auth-card">
            <div class="row g-0">
                <!-- Sisi Kiri / Banner Deskripsi -->
                <div class="col-lg-5 auth-side-banner">
                    <div>
                        <div class="brand-pill mb-4">
                            <i class="bi bi-water"></i> Smart Fishery
                        </div>
                        <h2 class="fw-bold mb-3">Sistem Informasi Pertambakan</h2>
                        <p class="text-white-50 small mb-4" style="line-height: 1.7;">
                            Platform terintegrasi petambak ikan dan Koperasi Unit Desa (KUD) untuk monitoring lingkungan air, estimasi panen, dan tata kelola hasil panen.
                        </p>

                        <div class="features-list d-none d-lg-block">
                            <div class="feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Klasifikasi Kualitas Air (Naïve Bayes)</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Estimasi Tonase Panen (Regresi Linier)</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Sinkronisasi Transparansi Harga KUD</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top border-white-10 text-white-50 small">
                        &copy; {{ date('Y') }} Smart Fishery. All Rights Reserved.
                    </div>
                </div>

                <!-- Sisi Kanan / Form Auth -->
                <div class="col-lg-7 auth-form-side position-relative">
                    <a href="{{ route('home') }}" class="text-secondary text-decoration-none small fw-medium position-absolute top-0 end-0 m-4">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                    </a>
                    <!-- Flash Message -->
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 small py-2.5 mb-3" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2.5 mb-3" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
