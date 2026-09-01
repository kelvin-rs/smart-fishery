@extends('layouts.petambak')

@section('title', 'Cek Kualitas Air Tambak')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Cek Kualitas Air Tambak</h4>
        <p class="text-muted small mb-0">Klasifikasi status lingkungan tambak via External Python ML Server (Gaussian Naïve Bayes).</p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Input Cek Kualitas Air (Sesuai Gambar 3.26) -->
    <div class="col-lg-5">
        <div class="card card-custom p-4">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-droplet-half text-primary me-1"></i> Form Uji Parameter Air
            </h5>
            <p class="text-muted small mb-4">Masukkan data kondisi air untuk dikirim ke Server Python.</p>

            <form action="{{ route('petambak.kualitas-air.proses') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="waktu" class="form-label small fw-semibold text-secondary">Waktu Pengukuran</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                        <select id="waktu" name="waktu" class="form-select" required>
                            <option value="Pagi" {{ old('waktu') === 'Pagi' ? 'selected' : '' }}>Pagi (07.00 - 09.00 WIB)</option>
                            <option value="Siang" {{ old('waktu') === 'Siang' ? 'selected' : '' }}>Siang (11.00 - 13.00 WIB)</option>
                            <option value="Sore" {{ old('waktu') === 'Sore' ? 'selected' : '' }}>Sore (15.00 - 17.00 WIB)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="jenis_ikan" class="form-label small fw-semibold text-secondary">Jenis Ikan / Udang</label>
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
                    <label for="suhu" class="form-label small fw-semibold text-secondary">Suhu Air (°C)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-thermometer-half"></i></span>
                        <input type="number" step="0.1" id="suhu" name="suhu" class="form-control" placeholder="Contoh: 28.0" value="{{ old('suhu', 28.0) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="ph" class="form-label small fw-semibold text-secondary">Kadar pH Air</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-speedometer"></i></span>
                        <input type="number" step="0.1" id="ph" name="ph" class="form-control" placeholder="Contoh: 7.7" value="{{ old('ph', 7.7) }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="padat_tebar" class="form-label small fw-semibold text-secondary">Kondisi Padat Tebar Lahan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-grid-3x3"></i></span>
                        <select id="padat_tebar" name="padat_tebar" class="form-select" required>
                            <option value="Normal" {{ old('padat_tebar') === 'Normal' ? 'selected' : '' }}>Normal (Sesuai Kapasitas)</option>
                            <option value="Tidak Normal" {{ old('padat_tebar') === 'Tidak Normal' ? 'selected' : '' }}>Tidak Normal (Terlalu Padat)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-semibold">
                    <i class="bi bi-send me-1"></i> Kirim ke Server ML Python
                </button>
            </form>
        </div>
    </div>

    <!-- Output Hasil Uji & Riwayat -->
    <div class="col-lg-7">
        @if(session('hasil_uji'))
            @php $hasil = session('hasil_uji'); @endphp
            <div class="card card-custom p-4 mb-4 border-2 {{ $hasil['hasil_prediksi'] === 'Normal' ? 'border-success' : 'border-danger' }}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark">Hasil Klasifikasi Tambak</h5>
                    <span class="badge {{ $hasil['hasil_prediksi'] === 'Normal' ? 'text-bg-success' : 'text-bg-danger' }} fs-6 px-3 py-2">
                        Status: {{ $hasil['hasil_prediksi'] }}
                    </span>
                </div>

                <p class="small text-secondary mb-3">
                    Berdasarkan respon Server Python untuk waktu <strong>{{ $hasil['waktu'] }}</strong> (Suhu: {{ $hasil['suhu'] }}°C, pH: {{ $hasil['ph'] }}), kondisi tambak dinyatakan:
                </p>

                <div class="p-3 bg-light rounded-3 border mb-3">
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Sumber Komputasi:</span>
                        <strong class="text-dark">{{ $hasil['source'] ?? 'Python ML Server' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mt-1">
                        <span>Status Kualitas:</span>
                        <strong class="{{ $hasil['hasil_prediksi'] === 'Normal' ? 'text-success' : 'text-danger' }}">{{ $hasil['hasil_prediksi'] }}</strong>
                    </div>
                </div>

                <div class="small text-muted">
                    <i class="bi bi-check-circle me-1 text-success"></i> Data respon klasifikasi telah tersimpan ke database.
                </div>
            </div>
        @endif

        <div class="card card-custom p-4">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-clock-history text-secondary me-1"></i> Riwayat Uji Kualitas Air Terakhir
            </h5>

            @if($history->isEmpty())
                <div class="p-4 text-center text-muted">Belum ada riwayat pengujian.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-secondary">
                            <tr>
                                <th>ID</th>
                                <th>Suhu</th>
                                <th>pH</th>
                                <th>Padat Tebar</th>
                                <th>Hasil Klasifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $h)
                                <tr>
                                    <td>#{{ $h->id }}</td>
                                    <td>{{ $h->suhu }} °C</td>
                                    <td>{{ $h->ph }}</td>
                                    <td><span class="badge text-bg-light border">{{ $h->kesehatan }}</span></td>
                                    <td>
                                        <span class="badge {{ strtolower($h->keterangan) === 'normal' ? 'text-bg-success' : 'text-bg-danger' }}">
                                            {{ $h->keterangan }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
    </div>
</div>
@endsection

@section('scripts')
@if(session('hasil_uji'))
@php $huji = session('hasil_uji'); @endphp
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: '{{ $huji['hasil_prediksi'] === 'Normal' ? 'success' : 'warning' }}',
            title: 'Hasil Klasifikasi: {{ $huji['hasil_prediksi'] }}',
            html: `
                <div class="text-start p-3 bg-light rounded-3 border mt-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Parameter Waktu:</span>
                        <strong>{{ $huji['waktu'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Suhu / pH:</span>
                        <strong>{{ $huji['suhu'] }}°C / pH {{ $huji['ph'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status Lingkungan:</span>
                        <strong class="{{ $huji['hasil_prediksi'] === 'Normal' ? 'text-success' : 'text-danger' }}">{{ $huji['hasil_prediksi'] }}</strong>
                    </div>
                </div>
            `,
            confirmButtonColor: '#0284c7',
            confirmButtonText: 'Tutup & Lihat Riwayat',
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
