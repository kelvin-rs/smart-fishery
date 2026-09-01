@extends('layouts.kud')

@section('title', 'Edit Profil Pengurus KUD')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-11 col-xl-10">
        <!-- Page Header -->
        <div class="mb-4">
            <h4 class="fw-bold text-dark mb-1">Pengaturan Profil Koperasi</h4>
            <p class="text-muted small mb-0">Perbarui informasi akun pengurus KUD, alamat email resmi, dan kata sandi akses.</p>
        </div>

        <div class="card card-custom p-4 p-md-5 mb-4 shadow-sm">
            <!-- Profile Avatar & Identity Header with Generous Padding -->
            <div class="p-4 p-md-4 mb-4 rounded-4 border bg-light d-flex flex-column flex-sm-row align-items-center gap-4">
                <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold shadow-sm flex-shrink-0" style="width: 86px; height: 86px; font-size: 2.4rem; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                    {{ strtoupper(substr($user->username ?? 'K', 0, 1)) }}
                </div>
                <div class="text-center text-sm-start flex-grow-1">
                    <h4 class="fw-bold text-dark mb-1.5">{{ $user->username }}</h4>
                    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2.5 mt-2">
                        <span class="badge bg-indigo-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill fw-bold" style="background: #eef2ff;">
                            <i class="bi bi-building me-1"></i>Pengurus KUD
                        </span>
                        <span class="badge bg-white text-secondary border px-3 py-1.5 rounded-pill">
                            <i class="bi bi-envelope me-1 text-primary"></i>{{ $user->email }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Edit Profile -->
            <form action="{{ route('kud.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-person-lines-fill text-primary"></i> Informasi Akun KUD
                    </h5>
                    <p class="text-muted small mb-3">Informasi dasar yang digunakan untuk identifikasi administrasi koperasi.</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 py-2.5 px-3"><i class="bi bi-person text-muted fs-6"></i></span>
                            <input type="text" name="username" class="form-control border-start-0 py-2.5 @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required placeholder="Masukkan username">
                        </div>
                        @error('username')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 py-2.5 px-3"><i class="bi bi-envelope text-muted fs-6"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 py-2.5 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required placeholder="kud@email.com">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock text-primary"></i> Keamanan & Kata Sandi
                    </h5>
                    <p class="text-muted small mb-3">Kosongkan bagian ini jika Anda tidak ingin mengganti kata sandi.</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">Password Saat Ini</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 py-2.5 px-3"><i class="bi bi-lock text-muted fs-6"></i></span>
                            <input type="password" name="password_current" class="form-control border-start-0 py-2.5 @error('password_current') is-invalid @enderror" placeholder="Masukkan password lama untuk konfirmasi">
                        </div>
                        @error('password_current')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 py-2.5 px-3"><i class="bi bi-key text-muted fs-6"></i></span>
                            <input type="password" name="password_new" class="form-control border-start-0 py-2.5 @error('password_new') is-invalid @enderror" placeholder="Minimal 6 karakter">
                        </div>
                        @error('password_new')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 py-2.5 px-3"><i class="bi bi-key-fill text-muted fs-6"></i></span>
                            <input type="password" name="password_new_confirmation" class="form-control border-start-0 py-2.5" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons with Generous Gap & Responsive Width -->
                <div class="d-flex flex-column-reverse flex-sm-row justify-content-end align-items-stretch align-items-sm-center gap-3 pt-4 border-top">
                    <a href="{{ route('kud.dashboard') }}" class="btn btn-light px-4 py-2.5 rounded-3 fw-semibold border text-secondary text-center">
                        <i class="bi bi-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4 py-2.5 rounded-3 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;">
                        <i class="bi bi-check2 fs-5"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
