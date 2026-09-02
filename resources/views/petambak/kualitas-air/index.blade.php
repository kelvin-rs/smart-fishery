@extends('layouts.petambak')

@section('title', 'Cek Kualitas Air Tambak')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Cek Kualitas Air Tambak</h4>
        <p class="text-muted small mb-0">Klasifikasi status lingkungan tambak via Gaussian Naïve Bayes.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card card-custom p-4 border-0 shadow-sm rounded-4">
            <div class="d-flex align-items-center mb-4 pb-2" style="gap: 1rem;">
                <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="bi bi-droplet-half fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1">Form Uji Parameter Air</h5>
                    <div class="text-muted small" style="font-size: 0.82rem;">Masukkan data kondisi air untuk uji parameter air.</div>
                </div>
            </div>

            <form action="{{ route('petambak.kualitas-air.proses') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="tanggal" class="form-label small fw-semibold text-secondary">Tanggal Pengujian</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                        <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="id_tambak" class="form-label small fw-semibold text-secondary">Pilih Unit Tambak</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <select id="id_tambak" name="id_tambak" class="form-select" required>
                            @foreach($tambaks as $t)
                                <option value="{{ $t->id }}" {{ old('id_tambak') == $t->id ? 'selected' : '' }}>
                                    Tambak {{ $t->nomor ?? $t->id }} ({{ $t->jenis_ikan }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

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
                    <i class="bi bi-send me-1"></i> Hitung Kualitas Air Tambak
                </button>
            </form>
        </div>
    </div>

    <!-- Output Hasil Uji & Riwayat -->
    <div class="col-lg-7" id="liveRightCol">
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
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-clock-history text-secondary me-1"></i> Riwayat Uji Kualitas Air
                </h5>
                <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1" id="kualitasAirTotalBadge">
                    {{ $history->total() }} Data Tercatat
                </span>
            </div>

            <!-- Toolbar Filter & Search -->
            <form action="{{ route('petambak.kualitas-air.index') }}" method="GET" class="row g-2 mb-3" id="formFilterKualitasAir">
                <div class="col-sm-6 col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari data..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}" title="Filter Tanggal Pengujian">
                </div>
                <div class="col-sm-4 col-md-2">
                    <select name="id_tambak" class="form-select form-select-sm">
                        <option value="">Semua Tambak</option>
                        @foreach($tambaks as $t)
                            <option value="{{ $t->id }}" {{ request('id_tambak') == $t->id ? 'selected' : '' }}>
                                Tambak {{ $t->nomor ?? $t->id }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="Normal" {{ request('status') === 'Normal' ? 'selected' : '' }}>Normal</option>
                        <option value="Tidak Normal" {{ request('status') === 'Tidak Normal' ? 'selected' : '' }}>Tidak Normal</option>
                    </select>
                </div>
                <div class="col-sm-4 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill" title="Terapkan Filter"><i class="bi bi-funnel"></i> Filter</button>
                    @if(request('search') || request('tanggal') || request('status') || request('id_tambak'))
                        <a href="{{ route('petambak.kualitas-air.index') }}" class="btn btn-light btn-sm text-secondary border" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                    @endif
                </div>
            </form>

            <div id="liveTableContainer">
                @if($history->isEmpty())
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                        Tidak ada riwayat pengujian yang cocok dengan filter.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-secondary">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Pengujian</th>
                                    <th>Unit Tambak</th>
                                    <th>Suhu Air</th>
                                    <th>Derajat pH</th>
                                    <th>Padat Tebar</th>
                                    <th>Status Lingkungan</th>
                                    <th class="text-center" style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $index => $h)
                                    @php
                                        $tglDisplay = $h->tanggal ? $h->tanggal->format('d/m/Y') : ($h->created_at ? $h->created_at->format('d/m/Y') : date('d/m/Y'));
                                        $tglFull = $h->tanggal ? $h->tanggal->format('d F Y') : ($h->created_at ? $h->created_at->format('d F Y') : date('d F Y'));
                                        $jamDisplay = $h->created_at ? $h->created_at->format('H:i') : date('H:i');
                                    @endphp
                                    <tr class="table-row-clickable" onclick="bukaDetailKualitasAir(this)"
                                        data-id="{{ $h->id }}"
                                        data-tanggal="{{ $tglFull }}"
                                        data-jam="{{ $jamDisplay }}"
                                        data-suhu="{{ $h->suhu }}"
                                        data-ph="{{ $h->ph }}"
                                        data-padat="{{ $h->kesehatan ?? 'Normal' }}"
                                        data-status="{{ $h->keterangan }}"
                                        data-normal="{{ $h->hasil_normal ?? '0.0000' }}"
                                        data-tidak="{{ $h->hasil_tidak ?? '0.0000' }}"
                                        data-tambak="{{ $h->tambak->nomor ?? ($h->id_tambak ?? '1') }}"
                                        data-lokasi="{{ $h->tambak->alamat ?? 'Sidoarjo, Jawa Timur' }}"
                                        data-komoditas="{{ $h->tambak->jenis_ikan ?? 'Bandeng' }}"
                                        title="Klik baris untuk melihat rincian pengujian">
                                        <td class="fw-semibold text-secondary">{{ $history->firstItem() + $index }}</td>
                                        <td>
                                            <span class="fw-semibold text-dark d-block">
                                                <i class="bi bi-calendar-event me-1 text-primary"></i>{{ $tglDisplay }}
                                            </span>
                                            <small class="text-muted" style="font-size: 0.74rem;">
                                                <i class="bi bi-clock me-1"></i>{{ $jamDisplay }} WIB
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-light border text-dark fw-bold">
                                                Tambak {{ $h->tambak->nomor ?? ($h->id_tambak ?? '1') }}
                                            </span>
                                        </td>
                                        <td><strong class="text-dark">{{ $h->suhu }}</strong> °C</td>
                                        <td><strong class="text-dark">{{ $h->ph }}</strong></td>
                                        <td><span class="badge text-bg-light border">{{ $h->kesehatan ?? 'Normal' }}</span></td>
                                        <td>
                                            <span class="badge {{ strtolower($h->keterangan) === 'normal' ? 'text-bg-success' : 'text-bg-danger' }}">
                                                {{ $h->keterangan }}
                                            </span>
                                        </td>
                                        <td class="text-center" onclick="event.stopPropagation()">
                                            <div class="d-inline-flex gap-1">
                                                <button type="button" class="btn btn-outline-primary btn-sm rounded-3 px-2 py-1" onclick="bukaDetailKualitasAir(this.closest('tr'))" title="Lihat Rincian">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                @if(Auth::id() === $h->user_id || Auth::user()->role === 'admin')
                                                    <form action="{{ route('petambak.kualitas-air.destroy', $h->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-3 px-2 py-1" onclick="hapusRiwayat(event, 'data uji tanggal {{ $tglDisplay }}')" title="Hapus Riwayat">
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
                        {{ $history->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Riwayat Kualitas Air -->
<div class="modal fade" id="modalDetailKualitasAir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-detail-dialog">
        <div class="modal-content modal-detail-content">
            <div class="modal-header modal-detail-header">
                <div>
                    <h5 class="modal-title fw-bold mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-droplet-half fs-4"></i> Rincian Pengujian Kualitas Air
                    </h5>
                    <div class="small opacity-90 d-flex align-items-center gap-1.5" id="mdlWaktuUji">
                        <i class="bi bi-calendar3"></i> <span>Tanggal Pengujian: -</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-detail-body">
                <!-- Status Banner -->
                <div class="modal-card-item d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3" id="mdlStatusCard" style="background-color: #f8fafc;">
                    <div>
                        <div class="small text-muted fw-semibold mb-1">Status Evaluasi Mutu Air:</div>
                        <h4 class="fw-bold mb-0" id="mdlStatusText">Kondisi Air Normal (Optimal)</h4>
                    </div>
                    <div>
                        <span class="badge fs-6 px-3 py-2 rounded-pill shadow-sm" id="mdlStatusBadge">Normal</span>
                    </div>
                </div>

                <!-- 4 Parameter Grid -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-md-3">
                        <div class="param-grid-card">
                            <div>
                                <span class="small text-muted fw-semibold d-block mb-2"><i class="bi bi-thermometer-half text-danger me-1"></i> Suhu Air</span>
                                <h4 class="fw-bold text-dark mb-0"><span id="mdlSuhu">-</span> <small class="fs-6 text-muted">°C</small></h4>
                            </div>
                            <div class="text-muted small mt-2 pt-2 border-top" style="font-size: 0.72rem;">Standar: 28 - 32 °C</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="param-grid-card">
                            <div>
                                <span class="small text-muted fw-semibold d-block mb-2"><i class="bi bi-water text-primary me-1"></i> Derajat pH</span>
                                <h4 class="fw-bold text-dark mb-0" id="mdlPh">-</h4>
                            </div>
                            <div class="text-muted small mt-2 pt-2 border-top" style="font-size: 0.72rem;">Standar: 7.5 - 8.5</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="param-grid-card">
                            <div>
                                <span class="small text-muted fw-semibold d-block mb-2"><i class="bi bi-grid-3x3 text-info me-1"></i> Padat Tebar</span>
                                <h5 class="fw-bold text-dark mb-0" id="mdlPadat">-</h5>
                            </div>
                            <div class="text-muted small mt-2 pt-2 border-top" style="font-size: 0.72rem;">Kondisi Kepadatan</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="param-grid-card">
                            <div>
                                <span class="small text-muted fw-semibold d-block mb-2"><i class="bi bi-geo-alt text-success me-1"></i> Unit Tambak</span>
                                <h5 class="fw-bold text-dark mb-1" id="mdlTambak">-</h5>
                            </div>
                            <div class="text-secondary small mt-1 lh-sm" id="mdlKomoditas" style="font-size: 0.75rem;">-</div>
                        </div>
                    </div>
                </div>

                <!-- Rekomendasi Teknis Budidaya -->
                <div class="modal-card-item" id="mdlRekomendasiBox" style="background-color: #f8fafc;">
                    <div class="fw-bold small mb-1.5 text-secondary d-flex align-items-center gap-2" id="mdlRekomendasiTitle">
                        <i class="bi bi-info-circle text-primary"></i> Rekomendasi Pengelolaan Air Tambak:
                    </div>
                    <p class="small text-muted mb-0 lh-base" id="mdlRekomendasiText">-</p>
                </div>
            </div>
            <div class="modal-footer modal-detail-footer">
                <button type="button" class="btn btn-secondary rounded-3 px-4 fw-semibold" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function bukaDetailKualitasAir(rowEl) {
        if (!rowEl) return;
        const d = rowEl.dataset;
        
        const tanggalStr = d.tanggal || '-';
        const jamStr = d.jam ? (' • Pukul ' + d.jam + ' WIB') : '';
        document.getElementById('mdlWaktuUji').innerHTML = `<i class="bi bi-calendar3 me-1"></i> Tanggal Pengujian: <strong>${tanggalStr}</strong> ${jamStr}`;
        
        document.getElementById('mdlSuhu').textContent = d.suhu || '-';
        document.getElementById('mdlPh').textContent = d.ph || '-';
        document.getElementById('mdlPadat').textContent = d.padat || 'Normal';
        document.getElementById('mdlTambak').textContent = 'Tambak ' + (d.tambak || '1');
        document.getElementById('mdlKomoditas').textContent = (d.komoditas || 'Bandeng') + ' • ' + (d.lokasi || 'Sidoarjo');

        const isNormal = (d.status || '').toLowerCase() === 'normal';
        const statusText = document.getElementById('mdlStatusText');
        const statusBadge = document.getElementById('mdlStatusBadge');
        const rekTitle = document.getElementById('mdlRekomendasiTitle');
        const rekText = document.getElementById('mdlRekomendasiText');

        if (isNormal) {
            statusText.textContent = 'Kondisi Air Normal (Optimal)';
            statusText.className = 'fw-bold mb-0 text-success';
            statusBadge.textContent = 'Normal';
            statusBadge.className = 'badge fs-6 px-3 py-2 rounded-pill text-bg-success shadow-sm';
            rekTitle.className = 'fw-bold small mb-2 text-success d-flex align-items-center gap-2';
            rekTitle.innerHTML = '<i class="bi bi-check-circle-fill fs-5 text-success"></i> Rekomendasi: Kondisi Air Stabil & Optimal';
            rekText.textContent = 'Parameter suhu dan pH berada dalam ambang batas ideal budidaya. Pertahankan aerasi rutin dan lakukan pemantauan berkala setiap pagi dan sore hari.';
        } else {
            statusText.textContent = 'Kondisi Air Tidak Normal (Perlu Tindakan)';
            statusText.className = 'fw-bold mb-0 text-danger';
            statusBadge.textContent = 'Tidak Normal';
            statusBadge.className = 'badge fs-6 px-3 py-2 rounded-pill text-bg-danger shadow-sm';
            rekTitle.className = 'fw-bold small mb-2 text-danger d-flex align-items-center gap-2';
            rekTitle.innerHTML = '<i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i> Perhatian: Penyesuaian Air Diperlukan!';
            rekText.textContent = 'Parameter air berada di luar batas ideal. Nyalakan aerator/kincir air tambahan, lakukan sirkulasi air baru secara bertahap, dan periksa pH air bila perlu penambahan kapur dolomit.';
        }

        const modal = new bootstrap.Modal(document.getElementById('modalDetailKualitasAir'));
        modal.show();
    }

    function hapusRiwayat(event, label) {
        event.preventDefault();
        const form = event.target.closest('form');
        window.liveDeleteAction(form, label);
    }
</script>

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
