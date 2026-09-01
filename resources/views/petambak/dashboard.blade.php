@extends('layouts.petambak')

@section('title', 'Dashboard Monitoring Tambak')

@section('content')
<!-- Custom Styles for Dashboard Refinements -->
<style>
    .stat-card-value {
        font-size: 1.15rem;
        font-weight: 800;
        line-height: 1.3;
        word-break: break-word;
    }

    .quick-feature-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.95rem;
        padding: 1.2rem 1.35rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
    }

    .quick-feature-card:hover {
        transform: translateY(-3px);
        background: #f8fafc;
        box-shadow: 0 10px 24px -4px rgba(2, 132, 199, 0.15);
    }

    .quick-feature-card.card-blue:hover {
        border-color: #0284c7;
        background: #f0f9ff;
    }

    .quick-feature-card.card-green:hover {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .quick-feature-card.card-indigo:hover {
        border-color: #6366f1;
        background: #eef2ff;
    }

    .quick-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }
</style>

<!-- Header & Tambak Selector -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Dashboard Monitoring Tambak</h4>
        <p class="text-muted small mb-0">Pemantauan metrik kualitas lingkungan air tambak dan proyeksi hasil panen.</p>
    </div>
    
    @if($tambakList->count() > 0)
        <div class="d-flex align-items-center gap-2 bg-white p-2 rounded-3 border shadow-sm">
            <span class="small fw-bold text-secondary ps-1"><i class="bi bi-geo-alt-fill text-primary me-1"></i>Pilih Kolam:</span>
            <form action="{{ route('petambak.dashboard') }}" method="GET" class="m-0">
                <select name="tambak_id" class="form-select form-select-sm border-0 bg-light fw-semibold" onchange="this.form.submit()">
                    @foreach($tambakList as $t)
                        <option value="{{ $t->id }}" {{ ($tambak && $tambak->id == $t->id) ? 'selected' : '' }}>
                            Tambak #{{ $t->nomor ?? $t->id }} ({{ $t->jenis_ikan }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    @endif
</div>

<!-- 4 Key Metric Cards -->
<div class="row g-3 mb-4">
    <!-- 1. Keadaan Tambak -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card shadow-sm position-relative overflow-hidden h-100">
            <div class="stat-icon {{ strtolower($statusTambak) === 'normal' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                <i class="bi {{ strtolower($statusTambak) === 'normal' ? 'bi-shield-check' : 'bi-shield-exclamation' }}"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold mb-1">Status Lingkungan</div>
                <div class="stat-card-value {{ strtolower($statusTambak) === 'normal' ? 'text-success' : 'text-danger' }}">
                    {{ $statusTambak }}
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Prediksi Panen -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card shadow-sm position-relative overflow-hidden h-100">
            <div class="stat-icon bg-primary-subtle text-primary">
                <i class="bi bi-calculator"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold mb-1">Prediksi Bobot Panen</div>
                <div class="stat-card-value text-primary">
                    {{ $prediksiPanen }}
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Jenis Ikan -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card shadow-sm position-relative overflow-hidden h-100">
            <div class="stat-icon bg-info-subtle text-info">
                <i class="bi bi-water"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold mb-1">Komoditas Budidaya</div>
                <div class="stat-card-value text-dark">
                    {{ $jenisIkan }}
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Harga Per KG dari KUD -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card shadow-sm position-relative overflow-hidden h-100">
            <div class="stat-icon bg-warning-subtle text-warning-emphasis">
                <i class="bi bi-tag-fill"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold mb-1">Harga Pasar KUD / Kg</div>
                <div class="stat-card-value text-dark">
                    Rp {{ number_format($hargaIkan, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Suhu & pH -->
<div class="card card-custom p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">
                <i class="bi bi-graph-up-arrow text-primary me-2"></i>Grafik Pengukuran Suhu & pH Air
            </h5>
            <p class="text-muted small mb-0">Pemantauan histori pencatatan data suhu dan pH air tambak</p>
        </div>

        <form action="{{ route('petambak.dashboard') }}" method="GET" class="d-flex gap-2 align-items-center m-0">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-calendar-event"></i></span>
                <input type="date" name="tanggal" value="{{ $filterDate }}" class="form-control form-control-sm">
            </div>
            <button type="submit" class="btn btn-outline-primary btn-sm fw-semibold">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if($filterDate)
                <a href="{{ route('petambak.dashboard') }}" class="btn btn-light btn-sm text-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div style="position: relative; height: 360px; width: 100%;">
        <canvas id="sensorChart"></canvas>
    </div>
</div>

<!-- Informasi Tambak Aktif & Aksi Cepat -->
<div class="row g-4">
    <!-- Card 1: Ringkasan Tambak Aktif -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 h-100">
            <h6 class="fw-bold text-dark mb-3.5 d-flex align-items-center">
                <i class="bi bi-info-circle-fill text-info me-2 fs-5"></i> Ringkasan Tambak Aktif
            </h6>
            @if($tambak)
                <div class="table-responsive">
                    <table class="table table-sm table-borderless align-middle mb-0">
                        <tbody>
                            <tr class="border-bottom">
                                <td class="text-muted py-2.5">Nomor / Kode Unit</td>
                                <td class="fw-bold text-dark py-2.5">Tambak #{{ $tambak->nomor ?? $tambak->id }}</td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="text-muted py-2.5">Lokasi Tambak</td>
                                <td class="fw-semibold text-dark py-2.5">{{ $tambak->alamat ?? 'Sidoarjo, Jawa Timur' }}</td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="text-muted py-2.5">Banyak Benih Ditebar</td>
                                <td class="fw-semibold text-dark py-2.5">{{ number_format($tambak->banyak_benih ?? 5000, 0, ',', '.') }} Ekor</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-2.5">Komoditas</td>
                                <td class="fw-semibold text-dark py-2.5"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1">{{ $tambak->jenis_ikan }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-4 text-center text-muted small">
                    <i class="bi bi-exclamation-circle fs-3 d-block mb-2 text-warning"></i>
                    Belum ada unit tambak yang terdaftar. Tambahkan melalui menu Data Tambak.
                </div>
            @endif
        </div>
    </div>

    <!-- Card 2: Akses Cepat Fitur Analisis -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 h-100">
            <h6 class="fw-bold text-dark mb-3.5 d-flex align-items-center">
                <i class="bi bi-lightning-charge-fill text-warning me-2 fs-5"></i> Akses Cepat Fitur Analisis
            </h6>
            <div class="d-grid gap-3">
                <!-- Item 1: Cek Kualitas Air -->
                <a href="{{ route('petambak.kualitas-air.index') }}" class="quick-feature-card card-blue">
                    <div class="d-flex align-items-center gap-3">
                        <div class="quick-icon-box bg-primary-subtle text-primary">
                            <i class="bi bi-droplet-half"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark fs-6 mb-0.5">Cek Kualitas Air Tambak</div>
                            <small class="text-muted fw-semibold">Uji suhu & pH untuk klasifikasi status air tambak</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-primary fs-5 fw-bold"></i>
                </a>

                <!-- Item 2: Prediksi Hasil Panen -->
                <a href="{{ route('petambak.prediksi.index') }}" class="quick-feature-card card-green">
                    <div class="d-flex align-items-center gap-3">
                        <div class="quick-icon-box bg-success-subtle text-success">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark fs-6 mb-0.5">Hitung Prediksi Hasil Panen</div>
                            <small class="text-muted fw-semibold">Estimasi tonase panen (Kg) sesuai siklus budidaya</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-success fs-5 fw-bold"></i>
                </a>

                <!-- Item 3: Kelola Data Pembelajaran -->
                <a href="{{ route('petambak.dataset.index') }}" class="quick-feature-card card-indigo">
                    <div class="d-flex align-items-center gap-3">
                        <div class="quick-icon-box bg-indigo-subtle text-primary" style="background: #eef2ff;">
                            <i class="bi bi-database-check text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark fs-6 mb-0.5">Kelola Data Pembelajaran</div>
                            <small class="text-muted fw-semibold">Jelajahi dan unggah data latih ke database sistem</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-secondary fs-5 fw-bold"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const ctx = document.getElementById('sensorChart').getContext('2d');
    const labels = @json($chartLabels);
    const suhuData = @json($chartSuhu);
    const phData = @json($chartPh);

    // Gradient background for Suhu & pH
    const gradSuhu = ctx.createLinearGradient(0, 0, 0, 350);
    gradSuhu.addColorStop(0, 'rgba(2, 132, 199, 0.25)');
    gradSuhu.addColorStop(1, 'rgba(2, 132, 199, 0.0)');

    const gradPh = ctx.createLinearGradient(0, 0, 0, 350);
    gradPh.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
    gradPh.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels.length ? labels : ['No Data'],
            datasets: [
                {
                    label: 'Suhu (°C)',
                    data: suhuData.length ? suhuData : [0],
                    borderColor: '#0284c7',
                    backgroundColor: gradSuhu,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#0284c7',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.35,
                    yAxisID: 'ySuhu'
                },
                {
                    label: 'pH Air',
                    data: phData.length ? phData : [0],
                    borderColor: '#10b981',
                    backgroundColor: gradPh,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.35,
                    yAxisID: 'yPh'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        boxHeight: 8,
                        padding: 16,
                        font: {
                            family: "'Plus Jakarta Sans', sans-serif",
                            weight: 600,
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { family: "'Plus Jakarta Sans'", weight: 700 },
                    bodyFont: { family: "'Plus Jakarta Sans'", weight: 500 },
                    padding: 12,
                    cornerRadius: 8,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: "'Plus Jakarta Sans'", size: 11 }
                    }
                },
                ySuhu: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Suhu (°C)',
                        font: { family: "'Plus Jakarta Sans'", weight: 600, size: 11 }
                    },
                    suggestedMin: 20,
                    suggestedMax: 40,
                    grid: {
                        color: 'rgba(226, 232, 240, 0.6)'
                    }
                },
                yPh: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'pH Air',
                        font: { family: "'Plus Jakarta Sans'", weight: 600, size: 11 }
                    },
                    suggestedMin: 4,
                    suggestedMax: 10,
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
</script>
@endsection
