@extends('layouts.petambak')

@section('title', 'Input Data')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Input Data Tambak</h4>
        <p class="text-muted small mb-0">Kelola dan tambahkan unit kolam tambak ikan dan udang milik akun Anda.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Input Data Tambak -->
    <div class="col-lg-5">
        <div class="card card-custom p-4">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-plus-circle-dotted text-primary me-1"></i> Form Tambah Kolam Tambak
            </h5>
            <p class="text-muted small mb-4">Lengkapi spesifikasi tambak untuk mengaktifkan pemantauan dan estimasi panen.</p>

            <form action="{{ route('petambak.tambak.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nomor" class="form-label small fw-semibold text-secondary">Nomor / Kode Tambak</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-hash"></i></span>
                        <input type="number" id="nomor" name="nomor" class="form-control" placeholder="Contoh: 1" value="{{ old('nomor', $tambaks->count() + 1) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label small fw-semibold text-secondary">Alamat / Lokasi Lahan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" id="alamat" name="alamat" class="form-control" placeholder="Contoh: Dusun Sumberrejo, Sidoarjo" value="{{ old('alamat') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="jenis_ikan" class="form-label small fw-semibold text-secondary">Jenis Ikan / Komoditas</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-water"></i></span>
                        <select id="jenis_ikan" name="jenis_ikan" class="form-select" required>
                            <option value="Bandeng" {{ old('jenis_ikan') === 'Bandeng' ? 'selected' : '' }}>Ikan Bandeng</option>
                            <option value="Vaname" {{ old('jenis_ikan') === 'Vaname' ? 'selected' : '' }}>Udang Vaname</option>
                            <option value="Windu" {{ old('jenis_ikan') === 'Windu' ? 'selected' : '' }}>Udang Windu</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="banyak_benih" class="form-label small fw-semibold text-secondary">Banyak Benih yang Ditebar (Ekor)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-layers"></i></span>
                        <input type="number" id="banyak_benih" name="banyak_benih" class="form-control" placeholder="Contoh: 5000" value="{{ old('banyak_benih', 5000) }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="luas_lahan" class="form-label small fw-semibold text-secondary">Luas Lahan Tambak (m²)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-bounding-box-circles"></i></span>
                        <input type="number" step="0.1" id="luas_lahan" name="luas_lahan" class="form-control" placeholder="Contoh: 800" value="{{ old('luas_lahan', 800) }}" required>
                    </div>
                    <div class="form-text small">Digunakan sebagai parameter model Machine Learning (Regresi Linier).</div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-semibold shadow-sm">
                    <i class="bi bi-save me-1"></i> Simpan Data Tambak
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Tambak Milik Petambak -->
    <div class="col-lg-7">
        <div class="card card-custom p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-list-ul text-primary me-1"></i> Daftar Tambak Terdaftar
                </h5>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1" id="tambakTotalBadge">
                    {{ $tambaks->total() }} Kolam Aktif
                </span>
            </div>

            <!-- Toolbar Filter & Search -->
            <form action="{{ route('petambak.tambak.index') }}" method="GET" class="row g-2 mb-3">
                <div class="col-sm-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari alamat / no. kolam..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-sm-4">
                    <select name="jenis_ikan" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Komoditas</option>
                        <option value="Bandeng" {{ request('jenis_ikan') === 'Bandeng' ? 'selected' : '' }}>Bandeng</option>
                        <option value="Vaname" {{ request('jenis_ikan') === 'Vaname' ? 'selected' : '' }}>Vaname</option>
                        <option value="Windu" {{ request('jenis_ikan') === 'Windu' ? 'selected' : '' }}>Windu</option>
                    </select>
                </div>
                <div class="col-sm-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel"></i></button>
                    @if(request('search') || request('jenis_ikan'))
                        <a href="{{ route('petambak.tambak.index') }}" class="btn btn-light btn-sm text-secondary" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                    @endif
                </div>
            </form>

            <div id="liveTableContainer">
                @if($tambaks->isEmpty())
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                        Tidak ada data tambak yang sesuai dengan filter pencarian.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="small text-secondary">
                                    <th>No. Kolam</th>
                                    <th>Komoditas</th>
                                    <th>Banyak Benih</th>
                                    <th>Luas Lahan</th>
                                    <th>Lokasi Tambak</th>
                                    <th class="text-center" style="width: 90px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tambaks as $t)
                                    <tr>
                                        <td>
                                            <span class="badge text-bg-primary px-2.5 py-1.5 fw-bold">{{ $t->nomor ?? $t->id }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ $t->jenis_ikan }}</span>
                                        </td>
                                        <td>{{ number_format($t->banyak_benih, 0, ',', '.') }} ekor</td>
                                        <td>{{ number_format($t->luas_lahan ?? 800, 0, ',', '.') }} m²</td>
                                        <td class="small text-muted">{{ $t->alamat }}</td>
                                        <td class="text-center">
                                            @if(Auth::id() === $t->user_id || Auth::user()->role === 'admin')
                                                <form action="{{ route('petambak.tambak.destroy', $t->id) }}" method="POST" class="d-inline form-delete-tambak">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-3 px-2 py-1" onclick="hapusTambak(event, 'Kolam {{ $t->nomor ?? $t->id }}')" title="Hapus Tambak">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-3 d-flex justify-content-end">
                        {{ $tambaks->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function hapusTambak(event, nomor) {
        event.preventDefault();
        const form = event.target.closest('form');
        Swal.fire({
            title: 'Hapus ' + nomor + '?',
            text: 'Data tambak beserta parameter yang terkait akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="bi bi-trash3"></i> Ya, Hapus',
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
@endsection
