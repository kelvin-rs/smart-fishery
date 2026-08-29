@extends('layouts.petambak')

@section('title', 'Tambah Sumber Data')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Tambah Sumber Data (Dataset)</h4>
        <p class="text-muted small mb-0">Impor dan perbarui data pembelajaran machine learning untuk kualitas air dan panen.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Upload Dataset Kualitas Air (Sesuai Gambar 3.24) -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-droplet text-primary me-1"></i> Tambah Sumber Data Kualitas Air
            </h5>
            <p class="text-muted small mb-4">Unggah file CSV data pengukuran suhu dan pH air untuk data latih Naïve Bayes.</p>

            <form action="{{ route('petambak.dataset.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kategori" value="kualitas_air">

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Pilih File Dataset (CSV)</label>
                    <input type="file" name="file_dataset" class="form-control" accept=".csv, .txt, .xlsx" required>
                </div>

                <div class="mb-4">
                    <div class="small fw-semibold text-secondary mb-2">Slot Waktu Data:</div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm flex-fill fw-semibold">
                            <i class="bi bi-upload me-1"></i> Unggah Pagi
                        </button>
                        <button type="submit" class="btn btn-outline-primary btn-sm flex-fill fw-semibold">
                            <i class="bi bi-upload me-1"></i> Unggah Siang
                        </button>
                        <button type="submit" class="btn btn-outline-primary btn-sm flex-fill fw-semibold">
                            <i class="bi bi-upload me-1"></i> Unggah Sore
                        </button>
                    </div>
                </div>
            </form>

            <div class="p-3 bg-light rounded-3 border mt-auto small text-muted">
                <i class="bi bi-info-circle me-1 text-info"></i> Format kolom CSV: <code>Suhu, pH, Padat_Tebar, Keterangan</code>
            </div>
        </div>
    </div>

    <!-- Form Upload Dataset Hasil Panen (Sesuai Gambar 3.24) -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-calculator text-success me-1"></i> Tambah Sumber Data Prediksi Panen
            </h5>
            <p class="text-muted small mb-4">Unggah data historis siklus bulan dan tonase hasil panen untuk model Regresi Linier.</p>

            <form action="{{ route('petambak.dataset.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kategori" value="prediksi_panen">

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Pilih File Dataset (CSV)</label>
                    <input type="file" name="file_dataset" class="form-control" accept=".csv, .txt, .xlsx" required>
                </div>

                <div class="mb-4">
                    <button type="submit" class="btn btn-success w-100 py-2 rounded-3 fw-semibold">
                        <i class="bi bi-cloud-arrow-up me-1"></i> Proses & Impor Data Panen
                    </button>
                </div>
            </form>

            <div class="p-3 bg-light rounded-3 border mt-auto small text-muted">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Total Data Latih Aktif:</span>
                    <strong class="text-dark">{{ number_format($totalDataLatih, 0, ',', '.') }} Sampel</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
