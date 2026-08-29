@extends('layouts.petambak')

@section('title', 'Data Tambak')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Pendaftaran & Data Tambak</h4>
        <p class="text-muted small mb-0">Kelola dan tambahkan unit kolam tambak ikan dan udang.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Input Data Tambak (Sesuai Gambar 3.22) -->
    <div class="col-lg-5">
        <div class="card card-custom p-4">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-plus-circle-dotted text-primary me-1"></i> Form Input Data Tambak
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

                <div class="mb-4">
                    <label for="banyak_benih" class="form-label small fw-semibold text-secondary">Banyak Benih yang Ditebar (Ekor)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-layers"></i></span>
                        <input type="number" id="banyak_benih" name="banyak_benih" class="form-control" placeholder="Contoh: 5000" value="{{ old('banyak_benih', 5000) }}" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-semibold">
                    <i class="bi bi-save me-1"></i> Simpan Data Tambak
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Tambak -->
    <div class="col-lg-7">
        <div class="card card-custom p-4">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-list-ul text-info me-1"></i> Daftar Tambak Terdaftar
            </h5>

            @if($tambaks->isEmpty())
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                    Belum ada data tambak. Silakan tambahkan melalui form di samping.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-secondary">
                                <th>No. Kolam</th>
                                <th>Komoditas</th>
                                <th>Banyak Benih</th>
                                <th>Lokasi Tambak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tambaks as $t)
                                <tr>
                                    <td>
                                        <span class="badge text-bg-primary px-2.5 py-1.5 fw-bold">#{{ $t->nomor ?? $t->id }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $t->jenis_ikan }}</span>
                                    </td>
                                    <td>{{ number_format($t->banyak_benih, 0, ',', '.') }} ekor</td>
                                    <td class="small text-muted">{{ $t->alamat }}</td>
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
