@extends('layouts.kud')

@section('title', 'Dashboard KUD')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Dashboard Koperasi Unit Desa (KUD)</h4>
        <p class="text-muted small mb-0">Pusat monitoring hasil panen petambak mitra dan pengelolaan harga komoditas.</p>
    </div>
</div>

<!-- 4 Key Stat Cards KUD -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-custom p-3 shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="bi bi-geo-alt fs-4"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Tambak Mitra</div>
                <div class="fs-4 fw-bold text-dark">{{ $totalTambak }} Unit</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-custom p-3 shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-success-subtle text-success p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="bi bi-box-seam fs-4"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Total Panen Masuk</div>
                <div class="fs-4 fw-bold text-dark">{{ number_format($totalPanenKg, 2, ',', '.') }} Kg</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-custom p-3 shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-warning-subtle text-warning-emphasis p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="bi bi-cash-stack fs-4"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Nilai Perputaran</div>
                <div class="fs-5 fw-bold text-dark">Rp {{ number_format($totalTransaksiRp, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-custom p-3 shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-info-subtle text-info p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="bi bi-tags fs-4"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Komoditas Aktif</div>
                <div class="fs-4 fw-bold text-dark">3 Jenis</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Harga Ikan Terkini -->
    <div class="col-lg-5">
        <div class="card card-custom p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-tag text-primary me-1"></i> Harga Pasar KUD Saat Ini
                </h5>
                <a href="{{ route('kud.harga.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    Ubah Harga
                </a>
            </div>

            <div class="list-group list-group-flush">
                @foreach($prices as $p)
                    <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-water text-info fs-5"></i>
                            <span class="fw-semibold">{{ $p->jenis_ikan }}</span>
                        </div>
                        <span class="fw-bold text-success fs-6">
                            Rp {{ number_format($p->harga, 0, ',', '.') }} / Kg
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Transaksi Panen Terakhir -->
    <div class="col-lg-7">
        <div class="card card-custom p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-clock-history text-secondary me-1"></i> Rekap Panen Terbaru
                </h5>
                <a href="{{ route('kud.panen.index') }}" class="btn btn-primary btn-sm">
                    Lihat Semua
                </a>
            </div>

            @if($recentHarvests->isEmpty())
                <p class="text-muted small mb-0 py-4 text-center">Belum ada transaksi panen yang tercatat.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-secondary">
                            <tr>
                                <th>Tanggal</th>
                                <th>Unit Tambak</th>
                                <th>Komoditas</th>
                                <th>Berat Panen</th>
                                <th>Total Uang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentHarvests as $rh)
                                <tr>
                                    <td>{{ $rh->tanggal_panen ? date('d/m/Y', strtotime($rh->tanggal_panen)) : '-' }}</td>
                                    <td>Tambak {{ $rh->tambak->nomor ?? $rh->id_tambak }}</td>
                                    <td><span class="badge text-bg-light border">{{ $rh->jenis_ikan }}</span></td>
                                    <td class="fw-semibold">{{ number_format($rh->banyak_panen, 2, ',', '.') }} Kg</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($rh->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
