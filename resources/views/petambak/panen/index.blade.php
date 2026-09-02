@extends('layouts.petambak')

@section('title', 'Informasi')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Informasi Hasil Panen</h4>
        <p class="text-muted small mb-0">Catatan transaksi timbangan dan total pendapatan penjualan ke KUD.</p>
    </div>

    <button type="button" class="btn btn-primary btn-sm fw-semibold px-3 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#modalTambahPanen">
        <i class="bi bi-plus-circle me-1"></i> Input Hasil Panen
    </button>
</div>

<!-- Tabel Hasil Panen (Sesuai Gambar 3.23) -->
<div class="card card-custom p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-receipt text-primary me-1"></i> Rekapitulasi Timbangan Petambak
        </h5>
        <div class="d-flex gap-2">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 align-self-center" id="panenTotalBadge">
                {{ $harvests->total() }} Transaksi
            </span>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="bi bi-printer me-1"></i> Cetak Dokumen
            </button>
        </div>
    </div>

    <!-- Toolbar Filter & Search -->
    <form action="{{ route('petambak.panen.index') }}" method="GET" class="row g-2 mb-3">
        <div class="col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari bobot panen / komoditas..." value="{{ request('search') }}">
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
                <a href="{{ route('petambak.panen.index') }}" class="btn btn-light btn-sm text-secondary" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
            @endif
        </div>
    </form>

    <div id="liveTableContainer">
        @if($harvests->isEmpty())
            <div class="p-5 text-center text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                Tidak ada catatan hasil panen yang cocok dengan filter.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelPanen">
                    <thead class="table-light small text-secondary">
                        <tr>
                            <th>No</th>
                            <th>Nama Petambak</th>
                            <th>Tanggal Panen</th>
                            <th>No. Timbangan</th>
                            <th>Banyak Panen (Kg)</th>
                            <th>Jenis Ikan</th>
                            <th>Total Nilai (Rp)</th>
                            <th class="text-center" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($harvests as $idx => $h)
                            <tr>
                                <td>{{ $harvests->firstItem() + $idx }}</td>
                                <td class="fw-semibold text-dark">{{ $user->username }}</td>
                                <td>{{ $h->tanggal_panen ? date('d-m-Y', strtotime($h->tanggal_panen)) : '-' }}</td>
                                <td><span class="badge text-bg-light border">{{ $h->id }}</span></td>
                                <td class="fw-bold text-dark">{{ number_format($h->banyak_panen, 2, ',', '.') }} Kg</td>
                                <td><span class="badge text-bg-info-subtle text-info-emphasis">{{ $h->jenis_ikan }}</span></td>
                                <td class="fw-bold text-success">Rp {{ number_format($h->total, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        <button type="button" class="btn btn-danger btn-sm rounded-pill px-2.5 py-1 fw-semibold" onclick="window.print()" title="Print Bukti">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                        @if(Auth::id() === $h->user_id || Auth::user()->role === 'admin')
                                            <form action="{{ route('petambak.panen.destroy', $h->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-outline-danger btn-sm rounded-3 px-2.5 py-1" onclick="hapusPanen(event, 'data panen tanggal {{ $h->tanggal_panen ? date('d-m-Y', strtotime($h->tanggal_panen)) : $h->id }}')" title="Hapus Data">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
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

<!-- Modal Tambah Data Panen -->
<div class="modal fade" id="modalTambahPanen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Input Data Panen Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('petambak.panen.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_tambak" value="{{ $user->id_tambak ?? 1 }}">
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tanggal Panen</label>
                        <input type="date" name="tanggal_panen" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Komoditas Ikan / Udang</label>
                        <select name="jenis_ikan" class="form-select" required>
                            <option value="Bandeng">Ikan Bandeng</option>
                            <option value="Vaname">Udang Vaname</option>
                            <option value="Windu">Udang Windu</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Bobot Hasil Panen (Kg)</label>
                        <input type="number" step="0.01" name="banyak_panen" class="form-control" placeholder="Contoh: 250.5" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 fw-semibold px-4">Simpan Data Panen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function hapusPanen(event, label) {
        event.preventDefault();
        const form = event.target.closest('form');
        Swal.fire({
            title: 'Hapus Data Panen?',
            text: `Apakah Anda yakin ingin menghapus ${label}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="bi bi-trash3"></i> Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endsection
