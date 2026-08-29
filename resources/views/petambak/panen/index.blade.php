@extends('layouts.petambak')

@section('title', 'Informasi Hasil Panen')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Tabel Informasi Hasil Panen</h4>
        <p class="text-muted small mb-0">Catatan transaksi timbangan dan total pendapatan penjualan ke KUD.</p>
    </div>

    <button type="button" class="btn btn-primary btn-sm fw-semibold px-3 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#modalTambahPanen">
        <i class="bi bi-plus-circle me-1"></i> Input Hasil Panen
    </button>
</div>

<!-- Tabel Hasil Panen (Sesuai Gambar 3.23) -->
<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-receipt text-primary me-1"></i> Rekapitulasi Timbangan Petambak
        </h5>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm fw-semibold">
            <i class="bi bi-printer me-1"></i> Cetak Dokumen
        </button>
    </div>

    @if($harvests->isEmpty())
        <div class="p-5 text-center text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
            Belum ada catatan hasil panen timbangan.
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
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($harvests as $idx => $h)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td class="fw-semibold text-dark">{{ $user->username }}</td>
                            <td>{{ $h->tanggal_panen ? date('d-m-Y', strtotime($h->tanggal_panen)) : '-' }}</td>
                            <td><span class="badge text-bg-light border">#{{ $h->id }}</span></td>
                            <td class="fw-bold text-dark">{{ number_format($h->banyak_panen, 2, ',', '.') }} Kg</td>
                            <td><span class="badge text-bg-info-subtle text-info-emphasis">{{ $h->jenis_ikan }}</span></td>
                            <td class="fw-bold text-success">Rp {{ number_format($h->total, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 py-1 fw-semibold" onclick="window.print()">
                                    <i class="bi bi-printer me-1"></i> PRINT
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
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
