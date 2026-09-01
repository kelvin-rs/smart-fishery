@extends('layouts.petambak')

@section('title', 'Edit Profil Petambak')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Page Header -->
        <div class="mb-4">
            <h4 class="fw-bold text-dark mb-1">Pengaturan Profil Akun</h4>
            <p class="text-muted small mb-0">Perbarui informasi akun, email, dan keamanan kata sandi Anda.</p>
        </div>

        <div class="card card-custom p-4 p-md-5 mb-4">
            <!-- Profile Avatar Header -->
            <div class="d-flex flex-column flex-sm-row align-items-center gap-3.5 pb-4 mb-4 border-bottom">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-2 shadow-sm" style="width: 72px; height: 72px;">
                    {{ strtoupper(substr($user->username ?? 'P', 0, 1)) }}
                </div>
                <div class="text-center text-sm-start">
                    <h5 class="fw-bold text-dark mb-1">{{ $user->username }}</h5>
                    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1">
                            <i class="bi bi-person-badge-fill me-1"></i>Petambak
                        </span>
                        <span class="text-muted small">
                            <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Edit Profile -->
            <form action="{{ route('petambak.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <h6 class="fw-bold text-dark mb-3">Informasi Akun</h6>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="username" class="form-control border-start-0 @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
                        </div>
                        @error('username')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

                <h6 class="fw-bold text-dark mb-1">Ubah Kata Sandi</h6>
                <p class="text-muted small mb-3">Kosongkan jika Anda tidak ingin mengganti kata sandi.</p>

                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">Password Saat Ini</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password_current" class="form-control border-start-0 @error('password_current') is-invalid @enderror" placeholder="Masukkan password lama untuk konfirmasi">
                        </div>
                        @error('password_current')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                            <input type="password" name="password_new" class="form-control border-start-0 @error('password_new') is-invalid @enderror" placeholder="Minimal 6 karakter">
                        </div>
                        @error('password_new')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key-fill text-muted"></i></span>
                            <input type="password" name="password_new_confirmation" class="form-control border-start-0" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2.5">
                    <a href="{{ route('petambak.dashboard') }}" class="btn btn-light px-4 py-2 fw-semibold text-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
