@extends('layouts.petambak')

@section('title', 'Prediksi Hasil Panen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Prediksi Potensi Hasil Panen</h4>
        <p class="text-muted small mb-0">Estimasi bobot panen (Kg) via External Python ML Server (Model Regresi Linier & SR).</p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Input Prediksi Panen (Sesuai Gambar 3.25) -->
    <div class="col-lg-5">
        <div class="card card-custom p-4">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-calculator text-primary me-1"></i> Form Prediksi Panen
            </h5>
            <p class="text-muted small mb-4">Lengkapi data siklus budidaya untuk dikirim ke Server Python.</p>

            <form action="{{ route('petambak.prediksi.proses') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="id_tambak" class="form-label small fw-semibold text-secondary">Pilih Unit Tambak</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <select id="id_tambak" name="id_tambak" class="form-select" required>
                            @foreach($tambaks as $t)
                                <option value="{{ $t->id }}">Tambak #{{ $t->nomor ?? $t->id }} ({{ $t->jenis_ikan }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="jenis_ikan" class="form-label small fw-semibold text-secondary">Komoditas yang Dibudidayakan</label>
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
                    <label for="bulan" class="form-label small fw-semibold text-secondary">Bulan Siklus Panen (Bulan ke-X)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                        <input type="number" id="bulan" name="bulan" min="1" max="12" class="form-control" placeholder="Contoh: 5" value="{{ old('bulan', 5) }}" required>
                    </div>
                    <div class="form-text small">Umumnya 3-4 bulan (Vaname), 5-6 bulan (Windu), 10-12 bulan (Bandeng).</div>
                </div>

                <div class="mb-4">
                    <label for="keadaan_tambak" class="form-label small fw-semibold text-secondary">Kondisi Status Tambak</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                        <select id="keadaan_tambak" name="keadaan_tambak" class="form-select" required>
                            <option value="Normal" {{ $statusKualitas === 'Normal' ? 'selected' : '' }}>Normal (Sesuai Standar Mutu)</option>
                            <option value="Tidak Normal" {{ $statusKualitas !== 'Normal' ? 'selected' : '' }}>Tidak Normal (Kualitas Air Kurang Optimal)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-semibold">
                    <i class="bi bi-send me-1"></i> Hitung via Server Python
                </button>
            </form>
        </div>
    </div>

    <!-- Output Hasil Prediksi & Riwayat -->
    <div class="col-lg-7">
        @if(session('hasil_prediksi'))
            @php $res = session('hasil_prediksi'); @endphp
            <div class="card card-custom p-4 mb-4 border-2 border-primary">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark">Estimasi Hasil Panen</h5>
                    <span class="badge text-bg-primary fs-6 px-3 py-2">
                        Bulan ke-{{ $res['bulan'] }}
                    </span>
                </div>

                <div class="p-3 bg-primary-subtle rounded-3 mb-3 text-center">
                    <div class="small text-primary fw-semibold mb-1">Perkiraan Total Bobot Panen</div>
                    <div class="display-6 fw-bold text-primary">{{ $res['teks_prediksi'] }}</div>
                </div>

                <div class="row g-2 mb-2 small text-secondary">
                    <div class="col-6">
                        <div class="p-2.5 bg-light rounded-2 border">
                            <span class="text-muted d-block">Komoditas:</span>
                            <strong class="text-dark">{{ $res['jenis_ikan'] }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2.5 bg-light rounded-2 border">
                            <span class="text-muted d-block">Status Tambak:</span>
                            <strong class="text-dark">{{ $res['keadaan_tambak'] }}</strong>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-2.5 bg-light rounded-2 border">
                            <span class="text-muted d-block">Sumber Komputasi:</span>
                            <span class="fw-semibold text-dark">{{ $res['source'] ?? 'Python ML Server' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card card-custom p-4">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-clock-history text-secondary me-1"></i> Riwayat Prediksi Panen Terakhir
            </h5>

            @if($history->isEmpty())
                <div class="p-4 text-center text-muted">Belum ada riwayat prediksi tersimpan.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-secondary">
                            <tr>
                                <th>ID</th>
                                <th>Unit Tambak</th>
                                <th>Hasil Prediksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $h)
                                <tr>
                                    <td>#{{ $h->id_hasil }}</td>
                                    <td>Tambak #{{ $h->id_tambak }}</td>
                                    <td><span class="badge text-bg-primary-subtle text-primary fw-bold px-2.5 py-1.5">{{ $h->prediksi }}</span></td>
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

@section('scripts')
@if(session('hasil_prediksi'))
@php $hpred = session('hasil_prediksi'); @endphp
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Prediksi Bobot Panen',
            html: `
                <div class="text-start p-3 bg-light rounded-3 border mt-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Unit Tambak:</span>
                        <strong>Tambak #{{ $hpred['id_tambak'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Komoditas & Siklus:</span>
                        <strong>{{ $hpred['jenis_ikan'] }} (Bulan ke-{{ $hpred['bulan'] }})</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kondisi Tambak:</span>
                        <span class="badge text-bg-info-subtle text-info-emphasis">{{ $hpred['keadaan_tambak'] }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-bold">Estimasi Bobot:</span>
                        <strong class="text-success fs-5">{{ $hpred['teks_prediksi'] }}</strong>
                    </div>
                </div>
            `,
            confirmButtonColor: '#0284c7',
            confirmButtonText: '<i class="bi bi-check2"></i> Selesai',
            showClass: {
                popup: 'animate__animated animate__bounceIn'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutDown'
            }
        });
    });
</script>
@endif
@endsection
