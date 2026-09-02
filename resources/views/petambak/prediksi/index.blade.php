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
        <div class="card card-custom p-4 border-0 shadow-sm rounded-4">
            <div class="d-flex align-items-center mb-4 pb-2" style="gap: 1rem;">
                <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="bi bi-calculator fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1">Form Prediksi Panen</h5>
                    <div class="text-muted small" style="font-size: 0.82rem;">Kalkulasi estimasi bobot via Model MLR</div>
                </div>
            </div>

            <form action="{{ route('petambak.prediksi.proses') }}" method="POST">
                @csrf

                <!-- Tanggal Prediksi -->
                <div class="mb-3">
                    <label for="tanggal" class="form-label small fw-bold text-dark mb-1.5">
                        <i class="bi bi-calendar-check text-primary me-1"></i> Tanggal Prediksi
                    </label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control rounded-3 py-2 px-3 border" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                </div>

                <!-- Unit Tambak & Tile Parameter Otomatis -->
                <div class="mb-3">
                    <label for="id_tambak" class="form-label small fw-bold text-dark mb-1.5">
                        <i class="bi bi-geo-alt text-primary me-1"></i> Pilih Unit Tambak
                    </label>
                    <select id="id_tambak" name="id_tambak" class="form-select rounded-3 py-2 px-3 border" required onchange="syncTambakParams()">
                        @foreach($tambaks as $t)
                            <option value="{{ $t->id }}" 
                                data-luas="{{ $t->luas_lahan ?? 800 }}" 
                                data-benih="{{ $t->banyak_benih ?? 5000 }}" 
                                data-ikan="{{ $t->jenis_ikan }}">
                                Tambak {{ $t->nomor ?? $t->id }} • {{ $t->jenis_ikan }}
                            </option>
                        @endforeach
                    </select>

                    <!-- 2 Tile Indikator Parameter Otomatis Tambak -->
                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <div class="rounded-3 border d-flex align-items-center gap-2" style="background-color: #f8fafc; border-color: #e2e8f0; padding: 0.65rem 0.85rem;">
                                <div class="rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                                    <i class="bi bi-rulers fs-5"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <span class="text-muted d-block fw-semibold" style="font-size: 0.68rem; letter-spacing: 0.03em;">LUAS LAHAN</span>
                                    <span class="fw-bold text-dark fs-6" id="lbl_luas">800</span> <small class="text-muted fw-normal" style="font-size: 0.72rem;">m²</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 border d-flex align-items-center gap-2" style="background-color: #f8fafc; border-color: #e2e8f0; padding: 0.65rem 0.85rem;">
                                <div class="rounded-2 bg-info-subtle text-info d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                                    <i class="bi bi-water fs-5"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <span class="text-muted d-block fw-semibold" style="font-size: 0.68rem; letter-spacing: 0.03em;">BANYAK BENIH</span>
                                    <span class="fw-bold text-dark fs-6" id="lbl_benih">5,000</span> <small class="text-muted fw-normal" style="font-size: 0.72rem;">ekor</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="luas_lahan" name="luas_lahan" value="800">
                    <input type="hidden" id="banyak_benih" name="banyak_benih" value="5000">
                </div>

                <!-- Komoditas -->
                <div class="mb-3">
                    <label for="jenis_ikan" class="form-label small fw-bold text-dark mb-1.5">
                        <i class="bi bi-water text-primary me-1"></i> Komoditas yang Dibudidayakan
                    </label>
                    <select id="jenis_ikan" name="jenis_ikan" class="form-select rounded-3 py-2 px-3 border" required>
                        <option value="Bandeng" {{ old('jenis_ikan') === 'Bandeng' ? 'selected' : '' }}>Ikan Bandeng</option>
                        <option value="Vaname" {{ old('jenis_ikan') === 'Vaname' ? 'selected' : '' }}>Udang Vaname</option>
                        <option value="Windu" {{ old('jenis_ikan') === 'Windu' ? 'selected' : '' }}>Udang Windu</option>
                    </select>
                </div>

                <!-- Bulan Siklus -->
                <div class="mb-3">
                    <label for="bulan" class="form-label small fw-bold text-dark mb-1.5">
                        <i class="bi bi-calendar-event text-primary me-1"></i> Umur Siklus Budidaya
                    </label>
                    <div class="input-group">
                        <input type="number" id="bulan" name="bulan" min="1" max="12" class="form-control rounded-start-3 py-2 px-3 border" placeholder="Contoh: 5" value="{{ old('bulan', 5) }}" required>
                        <span class="input-group-text rounded-end-3 bg-light text-muted small fw-semibold px-3">Bulan</span>
                    </div>
                    <div class="form-text text-muted small mt-1" style="font-size: 0.74rem;">
                        Rekomendasi: 3–4 bulan (Vaname), 5–6 bulan (Windu), 10–12 bulan (Bandeng).
                    </div>
                </div>

                <!-- Kondisi Mutu Air -->
                <div class="mb-4">
                    <label for="keadaan_tambak" class="form-label small fw-bold text-dark mb-1.5">
                        <i class="bi bi-shield-check text-primary me-1"></i> Kondisi Mutu Air Tambak
                    </label>
                    <select id="keadaan_tambak" name="keadaan_tambak" class="form-select rounded-3 py-2 px-3 border" required>
                        <option value="Normal" {{ $statusKualitas === 'Normal' ? 'selected' : '' }}>Normal (Sesuai Standar Mutu)</option>
                        <option value="Tidak Normal" {{ $statusKualitas !== 'Normal' ? 'selected' : '' }}>Tidak Normal (Kualitas Air Kurang Optimal)</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-cpu-fill"></i> Hitung Prediksi Panen
                </button>
            </form>
        </div>
    </div>

    <!-- Output Hasil Prediksi & Riwayat -->
    <div class="col-lg-7" id="liveRightCol">
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
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-clock-history text-secondary me-1"></i> Riwayat Prediksi Panen
                </h5>
                <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1" id="prediksiTotalBadge">
                    {{ $history->total() }} Data Tercatat
                </span>
            </div>

            <!-- Toolbar Filter & Search -->
            <form action="{{ route('petambak.prediksi.index') }}" method="GET" class="row g-2 mb-3" id="formFilterPrediksi">
                <div class="col-sm-6 col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari data..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}" title="Filter Tanggal Prediksi">
                </div>
                <div class="col-sm-4 col-md-2">
                    <select name="jenis_ikan" class="form-select form-select-sm">
                        <option value="">Semua Komoditas</option>
                        <option value="Bandeng" {{ request('jenis_ikan') === 'Bandeng' ? 'selected' : '' }}>Bandeng</option>
                        <option value="Vaname" {{ request('jenis_ikan') === 'Vaname' ? 'selected' : '' }}>Vaname</option>
                        <option value="Windu" {{ request('jenis_ikan') === 'Windu' ? 'selected' : '' }}>Windu</option>
                    </select>
                </div>
                <div class="col-sm-4 col-md-2">
                    <select name="keadaan" class="form-select form-select-sm">
                        <option value="">Semua Kondisi</option>
                        <option value="Normal" {{ request('keadaan') === 'Normal' ? 'selected' : '' }}>Normal</option>
                        <option value="Tidak Normal" {{ request('keadaan') === 'Tidak Normal' ? 'selected' : '' }}>Tidak Normal</option>
                    </select>
                </div>
                <div class="col-sm-4 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill" title="Terapkan Filter"><i class="bi bi-funnel"></i> Filter</button>
                    @if(request('search') || request('tanggal') || request('jenis_ikan') || request('keadaan'))
                        <a href="{{ route('petambak.prediksi.index') }}" class="btn btn-light btn-sm text-secondary border" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                    @endif
                </div>
            </form>

            <div id="liveTableContainer">
                @if($history->isEmpty())
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                        Tidak ada riwayat prediksi yang cocok dengan filter.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-secondary">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Prediksi</th>
                                    <th>Unit Tambak</th>
                                    <th>Komoditas</th>
                                    <th>Siklus</th>
                                    <th>Kondisi Air</th>
                                    <th>Estimasi Bobot</th>
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
                                    <tr class="table-row-clickable" onclick="bukaDetailPrediksi(this)"
                                        data-id="{{ $h->id_hasil }}"
                                        data-tanggal="{{ $tglFull }}"
                                        data-jam="{{ $jamDisplay }}"
                                        data-tambak="Tambak {{ $h->tambak->nomor ?? $h->id_tambak }}"
                                        data-lokasi="{{ $h->tambak->alamat ?? 'Sidoarjo, Jawa Timur' }}"
                                        data-komoditas="{{ $h->jenis_ikan ?? ($h->tambak->jenis_ikan ?? 'Bandeng') }}"
                                        data-bulan="{{ $h->bulan ?? '-' }}"
                                        data-kondisi="{{ $h->keadaan_tambak ?? 'Normal' }}"
                                        data-luas="{{ number_format($h->tambak->luas_lahan ?? 800, 0, ',', '.') }}"
                                        data-benih="{{ number_format($h->tambak->banyak_benih ?? 5000, 0, ',', '.') }}"
                                        data-prediksi="{{ $h->prediksi }}"
                                        title="Klik baris untuk melihat rincian prediksi">
                                        <td class="fw-semibold text-secondary">{{ $history->firstItem() + $index }}</td>
                                        <td>
                                            <span class="fw-semibold text-dark d-block">
                                                <i class="bi bi-calendar-event me-1 text-primary"></i>{{ $tglDisplay }}
                                            </span>
                                            <small class="text-muted" style="font-size: 0.74rem;">
                                                <i class="bi bi-clock me-1"></i>{{ $jamDisplay }} WIB
                                            </small>
                                        </td>
                                        <td class="fw-semibold text-dark">Tambak {{ $h->tambak->nomor ?? $h->id_tambak }}</td>
                                        <td><span class="badge text-bg-light border text-dark">{{ $h->jenis_ikan ?? ($h->tambak->jenis_ikan ?? '-') }}</span></td>
                                        <td><span class="badge text-bg-secondary-subtle text-secondary">Bulan {{ $h->bulan ?? '-' }}</span></td>
                                        @php
                                            $kondisiRaw = $h->keadaan_tambak ?: 'Normal';
                                            $isNormal = strtolower(trim($kondisiRaw)) === 'normal';
                                        @endphp
                                        <td>
                                            <span class="badge {{ $isNormal ? 'text-bg-success' : 'text-bg-danger' }}">
                                                {{ $isNormal ? 'Normal' : 'Tidak Normal' }}
                                            </span>
                                        </td>
                                        <td><span class="badge text-bg-primary-subtle text-primary fw-bold px-2.5 py-1.5">{{ $h->prediksi }}</span></td>
                                        <td class="text-center" onclick="event.stopPropagation()">
                                            <div class="d-inline-flex gap-1">
                                                <button type="button" class="btn btn-outline-primary btn-sm rounded-3 px-2 py-1" onclick="bukaDetailPrediksi(this.closest('tr'))" title="Lihat Rincian">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                @if(Auth::id() === $h->user_id || Auth::user()->role === 'admin')
                                                    <form action="{{ route('petambak.prediksi.destroy', $h->id_hasil) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-3 px-2 py-1" onclick="hapusPrediksi(event, 'prediksi panen Tambak {{ $h->tambak->nomor ?? $h->id_tambak }}')" title="Hapus Riwayat">
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
</div>

<!-- Modal Detail Riwayat Prediksi Panen -->
<div class="modal fade" id="modalDetailPrediksiPanen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-detail-dialog">
        <div class="modal-content modal-detail-content">
            <div class="modal-header modal-detail-header">
                <div>
                    <h5 class="modal-title fw-bold mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-graph-up-arrow fs-4"></i> Rincian Estimasi Hasil Panen
                    </h5>
                    <div class="small opacity-90 d-flex align-items-center gap-1.5" id="mdlPredWaktu">
                        <i class="bi bi-calendar3"></i> <span>Tanggal Prediksi: -</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-detail-body">
                <!-- Highlight Box Estimasi Bobot -->
                <div class="modal-card-item text-center" style="background-color: #f8fafc;">
                    <span class="small text-secondary fw-semibold text-uppercase letter-spacing mb-1 d-block" style="font-size: 0.78rem;">
                        Estimasi Bobot Panen
                    </span>
                    <div class="display-5 fw-bold text-dark my-1" id="mdlPredBobot">-</div>
                    <div class="small text-muted">
                        <span id="mdlPredIkan" class="fw-semibold text-secondary">-</span> • Siklus <span id="mdlPredBulan" class="fw-semibold text-secondary">-</span>
                    </div>
                </div>

                <!-- 4 Parameter Grid -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-md-3">
                        <div class="param-grid-card">
                            <div>
                                <span class="small text-muted fw-semibold d-block mb-2"><i class="bi bi-geo-alt text-primary me-1"></i> Unit Tambak</span>
                                <h5 class="fw-bold text-dark mb-1" id="mdlPredTambak">-</h5>
                            </div>
                            <div class="text-secondary small mt-1 lh-sm" id="mdlPredLokasi" style="font-size: 0.75rem;">-</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="param-grid-card">
                            <div>
                                <span class="small text-muted fw-semibold d-block mb-2"><i class="bi bi-rulers text-warning me-1"></i> Luas Lahan</span>
                                <h5 class="fw-bold text-dark mb-0"><span id="mdlPredLuas">-</span> <small class="fs-6 text-muted">m²</small></h5>
                            </div>
                            <div class="text-muted small mt-2 pt-2 border-top" style="font-size: 0.72rem;">Kapasitas Tambak</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="param-grid-card">
                            <div>
                                <span class="small text-muted fw-semibold d-block mb-2"><i class="bi bi-water text-info me-1"></i> Benih Ditebar</span>
                                <h5 class="fw-bold text-dark mb-0"><span id="mdlPredBenih">-</span> <small class="fs-6 text-muted">ekor</small></h5>
                            </div>
                            <div class="text-muted small mt-2 pt-2 border-top" style="font-size: 0.72rem;">Populasi Awal</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="param-grid-card">
                            <div>
                                <span class="small text-muted fw-semibold d-block mb-2"><i class="bi bi-activity text-success me-1"></i> Kondisi Air</span>
                                <h5 class="fw-bold mb-0" id="mdlPredKondisi">-</h5>
                            </div>
                            <div class="text-muted small mt-2 pt-2 border-top" style="font-size: 0.72rem;">Status Mutu Air</div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Metode Estimasi (Ringkas & Soft) -->
                <div class="modal-card-item" style="background-color: #f8fafc;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold text-secondary">
                            <i class="bi bi-cpu me-1.5 text-primary"></i> Algoritma Machine Learning:
                        </span>
                        <span class="badge text-bg-light border text-secondary fw-semibold px-2.5 py-1">
                            Multiple Linear Regression (MLR)
                        </span>
                    </div>
                </div>

                <!-- Rekomendasi Panen -->
                <div class="modal-card-item" id="mdlPredRekBox" style="background-color: #f8fafc;">
                    <div class="fw-bold small mb-1.5 text-secondary d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-primary"></i> Catatan & Rekomendasi Panen:
                    </div>
                    <p class="small text-muted mb-0 lh-base" id="mdlPredRekText">-</p>
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
    function bukaDetailPrediksi(rowEl) {
        if (!rowEl) return;
        const d = rowEl.dataset;

        const tanggalStr = d.tanggal || '-';
        const jamStr = d.jam ? (' • Pukul ' + d.jam + ' WIB') : '';
        document.getElementById('mdlPredWaktu').innerHTML = `<i class="bi bi-calendar3 me-1"></i> Tanggal Prediksi: <strong>${tanggalStr}</strong> ${jamStr}`;
        
        document.getElementById('mdlPredBobot').textContent = d.prediksi || '-';
        document.getElementById('mdlPredIkan').textContent = d.komoditas || 'Komoditas Tambak';
        document.getElementById('mdlPredBulan').textContent = 'Bulan ke-' + (d.bulan || '-');
        document.getElementById('mdlPredTambak').textContent = d.tambak || '-';
        document.getElementById('mdlPredLokasi').textContent = d.lokasi || 'Sidoarjo, Jawa Timur';
        document.getElementById('mdlPredLuas').textContent = d.luas || '800';
        document.getElementById('mdlPredBenih').textContent = d.benih || '5.000';

        const kondisiEl = document.getElementById('mdlPredKondisi');
        const rekText = document.getElementById('mdlPredRekText');
        const isNormal = (d.kondisi || '').toLowerCase() === 'normal';

        if (isNormal) {
            kondisiEl.textContent = 'Normal';
            kondisiEl.className = 'fw-bold text-success mb-0';
            rekText.textContent = `Kondisi tambak tergolong stabil dan sehat. Estimasi bobot ${d.prediksi} dapat dicapai optimal pada bulan ke-${d.bulan}. Pastikan pakan berkualitas dan sirkulasi air tetap terjaga hingga jadwal panen tiba.`;
        } else {
            kondisiEl.textContent = 'Tidak Normal';
            kondisiEl.className = 'fw-bold text-danger mb-0';
            rekText.textContent = `Perhatian: Kualitas air pada status Tidak Normal. Pertumbuhan bobot dapat mengalami deviasi atau penurunan survival rate jika parameter air tidak segera disesuaikan sebelum masa panen bulan ke-${d.bulan}.`;
        }

        const modal = new bootstrap.Modal(document.getElementById('modalDetailPrediksiPanen'));
        modal.show();
    }

    function hapusPrediksi(event, label) {
        event.preventDefault();
        const form = event.target.closest('form');
        window.liveDeleteAction(form, label);
    }

    function syncTambakParams() {
        const select = document.getElementById('id_tambak');
        if (!select) return;
        const selectedOpt = select.options[select.selectedIndex];
        if (!selectedOpt) return;

        const luas = selectedOpt.getAttribute('data-luas') || 800;
        const benih = selectedOpt.getAttribute('data-benih') || 5000;
        const ikan = selectedOpt.getAttribute('data-ikan');

        const lblLuas = document.getElementById('lbl_luas');
        const lblBenih = document.getElementById('lbl_benih');
        const inputLuas = document.getElementById('luas_lahan');
        const inputBenih = document.getElementById('banyak_benih');
        const selectIkan = document.getElementById('jenis_ikan');

        if (lblLuas) lblLuas.textContent = Number(luas).toLocaleString('id-ID');
        if (lblBenih) lblBenih.textContent = Number(benih).toLocaleString('id-ID');
        if (inputLuas) inputLuas.value = luas;
        if (inputBenih) inputBenih.value = benih;
        if (ikan && selectIkan) {
            selectIkan.value = ikan;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        syncTambakParams();
    });
</script>

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
                        <strong>Tambak {{ $hpred['id_tambak'] }}</strong>
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
