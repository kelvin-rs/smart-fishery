@extends('layouts.petambak')

@section('title', 'Tambah Sumber Data')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Tambah Sumber Data</h4>
        <p class="text-muted small mb-0">Kelola dan unggah dataset pembelajaran kualitas air dan proyeksi hasil panen.</p>
    </div>
</div>

<!-- Status Card Ringkasan Sumber Data -->
<div class="row g-3 mb-4">
    <div class="col-sm-6">
        <div class="stat-card shadow-sm">
            <div class="stat-icon bg-primary-subtle text-primary">
                <i class="bi bi-droplet-half"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Sumber Data Kualitas Air</div>
                <div class="fs-5 fw-bold text-dark">{{ number_format($totalDataKualitas, 0, ',', '.') }} Sampel</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="stat-card shadow-sm">
            <div class="stat-icon bg-success-subtle text-success">
                <i class="bi bi-calculator"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Sumber Data Prediksi Panen</div>
                <div class="fs-5 fw-bold text-dark">{{ number_format($totalDataPrediksi, 0, ',', '.') }} Record</div>
            </div>
        </div>
    </div>
</div>

<!-- Tab Navigasi Kategori Sumber Data -->
<ul class="nav nav-pills mb-4 gap-2 bg-white p-2 rounded-4 border shadow-sm" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="{{ route('petambak.dataset.index', ['tab' => 'kualitas_air']) }}" class="nav-link rounded-3 fw-bold px-4 py-2.5 d-flex align-items-center gap-2 {{ $tab === 'kualitas_air' ? 'active' : 'text-secondary' }}">
            <i class="bi bi-droplet-half"></i>
            <span>Tambah Sumber Data Kualitas Air</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('petambak.dataset.index', ['tab' => 'prediksi_panen']) }}" class="nav-link rounded-3 fw-bold px-4 py-2.5 d-flex align-items-center gap-2 {{ $tab === 'prediksi_panen' ? 'active' : 'text-secondary' }}">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Tambah Sumber Data Prediksi Hasil Panen</span>
        </a>
    </li>
</ul>

@if($tab === 'kualitas_air')
    <!-- SEKSI 1: SUMBER DATA KUALITAS AIR -->
    <div class="row g-4 mb-4">
        <!-- Form Upload Dataset Kualitas Air -->
        <div class="col-lg-12">
            <div class="card card-custom p-4">
                <h5 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                    <i class="bi bi-cloud-arrow-up text-primary"></i> Unggah Sumber Data Kualitas Air (Naïve Bayes)
                </h5>
                <p class="text-muted small mb-3">Unggah file CSV baru untuk data latih klasifikasi air (parameter: <code>suhu, ph, padat_tebar/kesehatan, keterangan</code>).</p>

                <form action="{{ route('petambak.dataset.upload') }}" method="POST" enctype="multipart/form-data" class="row g-3 align-items-end">
                    @csrf
                    <input type="hidden" name="kategori" value="kualitas_air">

                    <div class="col-md-9">
                        <label class="form-label small fw-semibold text-secondary">Pilih File Dataset Kualitas Air (.csv, .txt)</label>
                        <input type="file" name="file_dataset" class="form-control" accept=".csv, .txt" required>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-semibold">
                            <i class="bi bi-upload me-1"></i> Simpan ke Database
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Data Kualitas Air -->
    <div class="card card-custom p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-table text-primary me-1"></i> Data Pembelajaran Kualitas Air (Tabel data_train)
            </h5>

            <form action="{{ route('petambak.dataset.index') }}" method="GET" class="d-flex gap-2 m-0">
                <input type="hidden" name="tab" value="kualitas_air">
                <input type="text" name="search_kualitas" value="{{ $searchKualitas }}" class="form-control form-control-sm" placeholder="Cari status / parameter..." style="max-width: 220px;">
                <button type="submit" class="btn btn-outline-primary btn-sm fw-semibold"><i class="bi bi-search"></i></button>
                @if($searchKualitas)
                    <a href="{{ route('petambak.dataset.index', ['tab' => 'kualitas_air']) }}" class="btn btn-light btn-sm text-secondary">Reset</a>
                @endif
            </form>
        </div>

        <div id="liveTableContainer">
            @if($dataKualitasList->isEmpty())
                <div class="p-4 text-center text-muted">Belum ada data kualitas air di database.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-3">
                        <thead class="table-light small text-secondary">
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th>Suhu Air</th>
                                <th>pH Air</th>
                                <th>Padat Tebar</th>
                                <th>Keterangan / Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataKualitasList as $index => $row)
                                <tr>
                                    <td>{{ $dataKualitasList->firstItem() + $index }}</td>
                                    <td><strong class="text-dark">{{ $row->suhu }}</strong> °C</td>
                                    <td><strong class="text-dark">{{ $row->ph }}</strong></td>
                                    <td><span class="badge text-bg-light border">{{ $row->kesehatan }}</span></td>
                                    <td>
                                        <span class="badge {{ strtolower($row->ket) === 'normal' ? 'text-bg-success' : 'text-bg-danger' }}">
                                            {{ $row->ket }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">
                        Menampilkan {{ $dataKualitasList->firstItem() }} - {{ $dataKualitasList->lastItem() }} dari {{ number_format($dataKualitasList->total(), 0, ',', '.') }} data
                    </small>
                    <div>
                        {{ $dataKualitasList->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

@else
    <!-- SEKSI 2: SUMBER DATA PREDIKSI HASIL PANEN -->
    <div class="row g-4 mb-4">
        <!-- Form Upload Dataset Prediksi Panen -->
        <div class="col-lg-12">
            <div class="card card-custom p-4">
                <h5 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                    <i class="bi bi-cloud-arrow-up text-success"></i> Unggah Sumber Data Prediksi Panen (Regresi Linier)
                </h5>
                <p class="text-muted small mb-3">Unggah file CSV baru untuk data latih model prediksi panen (parameter: <code>id_tambak, prediksi</code>).</p>

                <form action="{{ route('petambak.dataset.upload') }}" method="POST" enctype="multipart/form-data" class="row g-3 align-items-end">
                    @csrf
                    <input type="hidden" name="kategori" value="prediksi_panen">

                    <div class="col-md-9">
                        <label class="form-label small fw-semibold text-secondary">Pilih File Dataset Prediksi Panen (.csv, .txt)</label>
                        <input type="file" name="file_dataset" class="form-control" accept=".csv, .txt" required>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success w-100 py-2.5 rounded-3 fw-semibold">
                            <i class="bi bi-upload me-1"></i> Simpan ke Database
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Data Prediksi Panen -->
    <div class="card card-custom p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-table text-success me-1"></i> Data Pembelajaran Prediksi Panen (Tabel prediksi)
            </h5>

            <form action="{{ route('petambak.dataset.index') }}" method="GET" class="d-flex gap-2 m-0">
                <input type="hidden" name="tab" value="prediksi_panen">
                <input type="text" name="search_prediksi" value="{{ $searchPrediksi }}" class="form-control form-control-sm" placeholder="Cari unit tambak / prediksi..." style="max-width: 220px;">
                <button type="submit" class="btn btn-outline-success btn-sm fw-semibold"><i class="bi bi-search"></i></button>
                @if($searchPrediksi)
                    <a href="{{ route('petambak.dataset.index', ['tab' => 'prediksi_panen']) }}" class="btn btn-light btn-sm text-secondary">Reset</a>
                @endif
            </form>
        </div>

        <div id="liveTableContainer">
            @if($dataPrediksiList->isEmpty())
                <div class="p-4 text-center text-muted">Belum ada data prediksi panen di database.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-3">
                        <thead class="table-light small text-secondary">
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th>Unit Tambak</th>
                                <th>Komoditas</th>
                                <th>Estimasi Bobot Panen (Prediksi)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataPrediksiList as $index => $row)
                                <tr>
                                    <td>{{ $dataPrediksiList->firstItem() + $index }}</td>
                                    <td><span class="badge text-bg-primary">Tambak {{ $row->tambak->nomor ?? $row->id_tambak }}</span></td>
                                    <td>{{ $row->tambak->jenis_ikan ?? 'Bandeng' }}</td>
                                    <td><strong class="text-success">{{ $row->prediksi }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">
                        Menampilkan {{ $dataPrediksiList->firstItem() }} - {{ $dataPrediksiList->lastItem() }} dari {{ number_format($dataPrediksiList->total(), 0, ',', '.') }} data
                    </small>
                    <div>
                        {{ $dataPrediksiList->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif

@endsection

@section('scripts')
<script>
    document.querySelectorAll('form[action="{{ route('petambak.dataset.upload') }}"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const fileInput = this.querySelector('input[type="file"]');
            if (!fileInput.files || !fileInput.files[0]) {
                Swal.fire({
                    icon: 'warning',
                    title: 'File Belum Dipilih',
                    text: 'Silakan pilih file CSV/TXT dataset terlebih dahulu.',
                    confirmButtonColor: '#0284c7'
                });
                return;
            }

            const fileName = fileInput.files[0].name;

            Swal.fire({
                title: 'Unggah Dataset?',
                text: `File "${fileName}" akan diimpor langsung ke basis data sistem.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0284c7',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="bi bi-upload"></i> Ya, Unggah Sekarang',
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
                    Swal.fire({
                        title: 'Mengimpor Data...',
                        text: 'Mohon tunggu, dataset sedang diproses ke database.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
