@extends('layouts.auth')

@section('title', 'Registrasi Akun')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Registrasi Akun</h3>
    <p class="text-muted small">Pilih peran akun dan lengkapi data pendaftaran.</p>
</div>

<form action="{{ route('register.post') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="username" class="form-label small fw-semibold text-secondary">Nama Lengkap / Username</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" id="username" name="username" class="form-control" placeholder="Contoh: Siska Amalia" value="{{ old('username') }}" required autofocus>
        </div>
    </div>

    <div class="mb-3">
        <label for="role" class="form-label small fw-semibold text-secondary">Tipe Akun (Peran)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <select id="role" name="role" class="form-select" required onchange="toggleTambakField(this.value)">
                <option value="petambak" {{ old('role', 'petambak') === 'petambak' ? 'selected' : '' }}>Petambak (Kelola Tambak & Panen)</option>
                <option value="kud" {{ old('role') === 'kud' ? 'selected' : '' }}>Pengurus KUD (Koperasi Unit Desa)</option>
            </select>
        </div>
    </div>

    <div class="mb-3" id="tambakField">
        <label for="id_tambak" class="form-label small fw-semibold text-secondary">Nomor / ID Tambak (Opsional)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
            <input type="text" id="id_tambak" name="id_tambak" class="form-control" placeholder="Contoh: 1 atau 2" value="{{ old('id_tambak') }}">
        </div>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label small fw-semibold text-secondary">Alamat Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
        </div>
    </div>

    <div class="row g-2 mb-4">
        <div class="col-md-6">
            <label for="password" class="form-label small fw-semibold text-secondary">Kata Sandi</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" id="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
            </div>
        </div>
        <div class="col-md-6">
            <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Ulangi Sandi</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi sandi" required>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary-custom w-100 mb-3">
        <i class="bi bi-person-plus me-1"></i> Daftar Sekarang
    </button>

    <div class="text-center small text-muted">
        Sudah memiliki akun? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-primary">Masuk di sini</a>
    </div>
</form>

<script>
    function toggleTambakField(role) {
        const field = document.getElementById('tambakField');
        if (role === 'kud') {
            field.classList.add('d-none');
        } else {
            field.classList.remove('d-none');
        }
    }
    toggleTambakField(document.getElementById('role').value);
</script>
@endsection
