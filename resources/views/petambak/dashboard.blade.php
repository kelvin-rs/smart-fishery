@extends('layouts.petambak')

@section('title', 'Dashboard Monitoring Tambak')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Dashboard Monitoring Tambak</h4>
        <p class="text-muted small mb-0">Pemantauan kondisi air real-time dan ringkasan prediksi potensi panen.</p>
    </div>
    
    @if($tambakList->count() > 1)
        <div class="d-flex align-items-center gap-2">
            <span class="small fw-semibold text-secondary">Pilih Tambak:</span>
            <form action="{{ route('petambak.dashboard') }}" method="GET" class="m-0">
                <select name="tambak_id" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()">
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

<!-- 4 Key Metric Cards (Sesuai Gambar 3.21) -->
<div class="row g-3 mb-4">
    <!-- 1. Keadaan Tambak -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card shadow-sm">
            <div class="stat-icon {{ strtolower($statusTambak) === 'normal' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Keadaan Tambak</div>
                <div class="fs-5 fw-bold {{ strtolower($statusTambak) === 'normal' ? 'text-success' : 'text-danger' }}">
                    {{ $statusTambak }}
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Prediksi Panen -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card shadow-sm">
            <div class="stat-icon bg-primary-subtle text-primary">
                <i class="bi bi-calculator"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Prediksi Panen</div>
                <div class="fs-5 fw-bold text-primary">{{ $prediksiPanen }}</div>
            </div>
        </div>
    </div>

    <!-- 3. Jenis Ikan -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card shadow-sm">
            <div class="stat-icon bg-info-subtle text-info">
                <i class="bi bi-water"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Komoditas Ikan</div>
                <div class="fs-5 fw-bold text-dark">{{ $jenisIkan }}</div>
            </div>
        </div>
    </div>

    <!-- 4. Harga Per KG dari KUD -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card shadow-sm">
            <div class="stat-icon bg-warning-subtle text-warning-emphasis">
                <i class="bi bi-tag-fill"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Harga Pasar KUD / Kg</div>
                <div class="fs-5 fw-bold text-dark">Rp {{ number_format($hargaIkan, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Suhu & pH Realtime -->
<div class="card card-custom p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">
                <i class="bi bi-graph-up text-primary me-1"></i> Grafik Temperatur Suhu dan pH Air
            </h5>
            <p class="text-muted small mb-0">Pencatatan data sensor harian (Pagi 07.00–09.00, Siang 11.00–13.00, Sore 15.00–17.00 WIB)</p>
        </div>

        <form action="{{ route('petambak.dashboard') }}" method="GET" class="d-flex gap-2 align-items-center m-0">
            <input type="date" name="tanggal" value="{{ $filterDate }}" class="form-control form-control-sm" style="width: auto;">
            <button type="submit" class="btn btn-outline-primary btn-sm fw-semibold">
                <i class="bi bi-funnel"></i> Filter
            </button>
            @if($filterDate)
                <a href="{{ route('petambak.dashboard') }}" class="btn btn-light btn-sm text-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div style="position: relative; height: 340px; width: 100%;">
        <canvas id="sensorChart"></canvas>
    </div>
</div>

<!-- Informasi Tambak Aktif -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card card-custom p-4 h-100">
            <h6 class="fw-bold text-dark mb-3">
                <i class="bi bi-info-circle text-info me-1"></i> Ringkasan Kolam Aktif
            </h6>
            @if($tambak)
                <div class="table-responsive">
                    <table class="table table-sm table-borderless align-middle mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted py-2">Nomor Tambak</td>
                                <td class="fw-bold py-2">#{{ $tambak->nomor ?? $tambak->id }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-2">Lokasi / Alamat</td>
                                <td class="fw-semibold py-2">{{ $tambak->alamat ?? 'Sidoarjo, Jawa Timur' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-2">Populasi Benih</td>
                                <td class="fw-semibold py-2">{{ number_format($tambak->banyak_benih ?? 5000, 0, ',', '.') }} Ekor</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-2">Jenis Budidaya</td>
                                <td class="fw-semibold py-2"><span class="badge text-bg-primary-subtle text-primary">{{ $tambak->jenis_ikan }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted small mb-0">Belum ada data tambak yang didaftarkan. Silakan tambahkan pada menu Data Tambak.</p>
            @endif
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-custom p-4 h-100">
            <h6 class="fw-bold text-dark mb-3">
                <i class="bi bi-lightning-charge text-warning-emphasis me-1"></i> Aksi Cepat Petambak
            </h6>
            <div class="d-grid gap-2">
                <a href="{{ route('petambak.kualitas-air.index') }}" class="btn btn-outline-primary text-start p-3 rounded-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold"><i class="bi bi-droplet-half me-2"></i>Cek Kualitas Air Tambak</div>
                        <small class="text-muted">Uji parameter suhu dan pH dengan metode Naïve Bayes</small>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </a>

                <a href="{{ route('petambak.prediksi.index') }}" class="btn btn-outline-success text-start p-3 rounded-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold"><i class="bi bi-calculator me-2"></i>Hitung Prediksi Hasil Panen</div>
                        <small class="text-muted">Estimasi bobot panen (Kg) metode Regresi Linier</small>
                    </div>
                    <i class="bi bi-chevron-right"></i>
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

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Suhu Air (°C)',
                    data: suhuData,
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(2, 132, 199, 0.1)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    yAxisID: 'ySuhu',
                    pointRadius: 4,
                    pointBackgroundColor: '#0284c7'
                },
                {
                    label: 'Kadar pH Air',
                    data: phData,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    yAxisID: 'yPh',
                    pointRadius: 4,
                    pointBackgroundColor: '#f59e0b'
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
                    labels: {
                        usePointStyle: true,
                        font: { family: 'Plus Jakarta Sans', weight: '600' }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { family: 'Plus Jakarta Sans' } }
                },
                ySuhu: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    min: 20,
                    max: 38,
                    title: { display: true, text: 'Suhu (°C)', font: { family: 'Plus Jakarta Sans', weight: '600' } },
                    grid: { color: '#f1f5f9' }
                },
                yPh: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    min: 4,
                    max: 12,
                    title: { display: true, text: 'Kadar pH', font: { family: 'Plus Jakarta Sans', weight: '600' } },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });

    // Real-time polling setiap 10 detik jika tidak sedang memfilter tanggal spesifik
    @if(!$filterDate)
        const tambakId = {{ $tambak->id ?? 1 }};
        setInterval(async () => {
            try {
                const res = await fetch(`/api/sensor/realtime/${tambakId}`);
                if (res.ok) {
                    const data = await res.json();
                    if (data.labels && data.labels.length > 0) {
                        chart.data.labels = data.labels;
                        chart.data.datasets[0].data = data.suhu;
                        chart.data.datasets[1].data = data.ph;
                        chart.update('none');
                    }
                }
            } catch (err) {
                // Silently ignore network hiccup
            }
        }, 10000);
    @endif
</script>
@endsection
