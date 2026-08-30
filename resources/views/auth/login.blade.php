@extends('layouts.auth')

@section('title', 'Masuk Akun')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Masuk ke Akun</h3>
    <p class="text-muted small">Silakan masukkan email dan kata sandi Anda.</p>
</div>

<form action="{{ route('login.post') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label small fw-semibold text-secondary">Alamat Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
        </div>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label small fw-semibold text-secondary">Kata Sandi</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            <button class="btn btn-toggle-password" type="button" id="togglePassword" aria-label="Tampilkan / Sembunyikan Kata Sandi">
                <i class="bi bi-eye" id="togglePasswordIcon"></i>
            </button>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
        </div>
        <a href="#" class="small text-decoration-none text-primary">Lupa Sandi?</a>
    </div>

    <button type="submit" class="btn btn-primary-custom w-100 mb-3">
        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
    </button>

    <div class="text-center small text-muted">
        Belum memiliki akun? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-primary">Daftar Akun Baru</a>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && icon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                
                if (isPassword) {
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        }
    });
</script>
@endsection

