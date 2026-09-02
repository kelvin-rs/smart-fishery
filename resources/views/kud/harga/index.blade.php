@extends('layouts.kud')

@section('title', 'Update Harga Ikan KUD')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Pembaruan Harga Pasar Komoditas</h4>
        <p class="text-muted small mb-0">Kelola harga beli KUD per kilogram untuk ikan bandeng, udang vaname, dan udang windu.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Update Harga (Sesuai Gambar 3.28) -->
    <div class="col-lg-5">
        <div class="card card-custom p-4">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-pencil-square text-primary me-1"></i> Form Update Harga Ikan
            </h5>
            <p class="text-muted small mb-4">Harga yang diperbarui akan otomatis digunakan dalam kalkulasi pendapatan penjualan petambak.</p>

            <form action="{{ route('kud.harga.update') }}" method="POST" id="formUpdateHarga" onsubmit="confirmUpdateHarga(event)">
                @csrf

                <div class="mb-3">
                    <label for="jenis_ikan" class="form-label small fw-semibold text-secondary">Jenis Ikan / Udang</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-water"></i></span>
                        <select id="jenis_ikan" name="jenis_ikan" class="form-select" required>
                            <option value="Bandeng">Ikan Bandeng</option>
                            <option value="Vaname">Udang Vaname</option>
                            <option value="Windu">Udang Windu</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="harga" class="form-label small fw-semibold text-secondary">Harga Baru (Rp / Kg)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" id="harga" name="harga" class="form-control" placeholder="Contoh: 25000" min="1000" step="500" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-semibold">
                    <i class="bi bi-check-lg me-1"></i> Terapkan Harga Baru
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Harga Saat Ini -->
    <div class="col-lg-7">
        <div class="card card-custom p-4" id="liveHargaCard">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-tags text-info me-1"></i> Daftar Harga Pasar KUD Saat Ini
            </h5>

            <div id="liveTableContainer">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-secondary">
                            <tr>
                                <th>No</th>
                                <th>Jenis Komoditas</th>
                                <th>Harga Beli / Kg</th>
                                <th>Status Pasar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prices as $idx => $p)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="fw-bold text-dark">{{ $p->jenis_ikan }}</td>
                                    <td class="fw-bold text-success fs-6">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                                    <td><span class="badge text-bg-success-subtle text-success">Aktif Berlaku</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmUpdateHarga(event) {
        event.preventDefault();
        const form = event.target;
        const komoditas = document.getElementById('jenis_ikan').value;
        const harga = document.getElementById('harga').value;

        if (!harga || harga < 1000) {
            Swal.fire({
                icon: 'warning',
                title: 'Nilai Tidak Valid',
                text: 'Harap masukkan nominal harga yang valid (minimal Rp 1.000).',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        const formatted = new Intl.NumberFormat('id-ID').format(harga);

        Swal.fire({
            title: 'Terapkan Harga Baru?',
            text: `Perbarui harga ${komoditas} menjadi Rp ${formatted} / Kg untuk seluruh petambak?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="bi bi-check-lg"></i> Ya, Terapkan',
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
