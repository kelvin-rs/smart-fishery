@extends('layouts.kud')

@section('title', 'Rekapitulasi Panen KUD')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Informasi Hasil Panen Petambak Mitra</h4>
        <p class="text-muted small mb-0">Rekapitulasi transaksi pembelian hasil panen dari seluruh petambak.</p>
    </div>

    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm fw-semibold">
            <i class="bi bi-printer me-1"></i> Cetak Rekapitulasi
        </button>
    </div>
</div>

<!-- 2 Metric Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card card-custom p-3 shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-box-seam fs-4"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Akumulasi Berat Panen</div>
                <div class="fs-4 fw-bold text-dark">{{ number_format($totalTonase, 2, ',', '.') }} Kg</div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-custom p-3 shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-success-subtle text-success p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-cash fs-4"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Total Nilai Pembelian</div>
                <div class="fs-4 fw-bold text-success">Rp {{ number_format($totalUang, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Informasi Hasil Panen KUD (Sesuai Gambar 3.27) -->
<div class="card card-custom p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-table text-primary me-1"></i> Tabel Transaksi Hasil Panen Petambak
        </h5>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 align-self-center" id="kudPanenTotalBadge">
            {{ $harvests->total() }} Transaksi
        </span>
    </div>

    <!-- Toolbar Filter & Search -->
    <form action="{{ route('kud.panen.index') }}" method="GET" class="row g-2 mb-3">
        <div class="col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama petambak / lokasi..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}" onchange="this.form.submit()" title="Filter Tanggal Panen">
        </div>
        <div class="col-sm-3">
            <select name="jenis_ikan" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Komoditas</option>
                <option value="Bandeng" {{ request('jenis_ikan') === 'Bandeng' ? 'selected' : '' }}>Bandeng</option>
                <option value="Vaname" {{ request('jenis_ikan') === 'Vaname' ? 'selected' : '' }}>Vaname</option>
                <option value="Windu" {{ request('jenis_ikan') === 'Windu' ? 'selected' : '' }}>Windu</option>
            </select>
        </div>
        <div class="col-sm-2 d-flex gap-1">
            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel"></i></button>
            @if(request('search') || request('tanggal') || request('jenis_ikan'))
                <a href="{{ route('kud.panen.index') }}" class="btn btn-light btn-sm text-secondary" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
            @endif
        </div>
    </form>

    <div id="liveTableContainer">
        @if($harvests->isEmpty())
            <div class="p-5 text-center text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                Tidak ada transaksi panen yang cocok dengan kriteria filter.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-secondary">
                        <tr>
                            <th>No</th>
                            <th>Kode Tambak</th>
                            <th>Petambak</th>
                            <th>Tanggal Panen</th>
                            <th>Lokasi Tambak</th>
                            <th>Banyak Panen (Kg)</th>
                            <th>Jenis Ikan</th>
                            <th>Total Pembayaran (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($harvests as $idx => $h)
                            <tr>
                                <td>{{ $harvests->firstItem() + $idx }}</td>
                                <td><span class="badge text-bg-primary-subtle text-primary fw-bold">Tambak {{ $h->tambak->nomor ?? $h->id_tambak }}</span></td>
                                <td class="fw-semibold text-dark">{{ $h->tambak->user->name ?? ($h->tambak->user->username ?? 'Petambak') }}</td>
                                <td>{{ $h->tanggal_panen ? date('d-m-Y', strtotime($h->tanggal_panen)) : '-' }}</td>
                                <td class="small text-muted">{{ $h->tambak->alamat ?? 'Sidoarjo, Jawa Timur' }}</td>
                                <td class="fw-bold text-dark">{{ number_format($h->banyak_panen, 2, ',', '.') }} Kg</td>
                                <td><span class="badge text-bg-info-subtle text-info-emphasis">{{ $h->jenis_ikan }}</span></td>
                                <td class="fw-bold text-success">Rp {{ number_format($h->total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="mt-3 d-flex justify-content-end">
                {{ $harvests->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
