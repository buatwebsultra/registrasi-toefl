@extends('layouts.app')

@section('content')
<style nonce="{{ $csp_nonce ?? '' }}">
    .profile-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
        border-radius: 1.5rem;
        padding: 3rem 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .profile-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
        opacity: 0.1;
        pointer-events: none;
    }

    .avatar-wrapper {
        position: relative;
        width: 150px;
        height: 150px;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #e0e7ff;
        color: #4338ca;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        border: 4px solid white;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
    }

    .camera-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: white;
        color: #4338ca;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        transition: transform 0.2s;
    }

    .camera-btn:hover {
        transform: scale(1.1);
        background: #f9fafb;
    }

    .badge-premium {
        background: rgba(99, 102, 241, 0.2);
        color: #c7d2fe;
        border: 1px solid rgba(99, 102, 241, 0.3);
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
    }

    .premium-card {
        border: none;
        border-radius: 1.25rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        background: white;
        margin-bottom: 2rem;
    }

    .premium-card .card-header {
        background: #f9fafb;
        border-bottom: 1px solid #f3f4f6;
        padding: 1.25rem 1.5rem;
        border-top-left-radius: 1.25rem;
        border-top-right-radius: 1.25rem;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .input-group-text {
        background: #f9fafb;
        border-right: none;
        color: #9ca3af;
    }

    .form-control {
        border-left: none;
        background: #f9fafb;
        padding: 0.625rem 0.75rem;
    }

    .form-control:focus {
        background: white;
        box-shadow: none;
        border-color: #6366f1;
    }

    .form-control:focus + .input-group-text,
    .input-group:focus-within .input-group-text {
        border-color: #6366f1;
        color: #6366f1;
    }

    .sidebar-nav .nav-link {
        border-radius: 0.75rem;
        margin-bottom: 0.5rem;
        padding: 0.75rem 1rem;
        color: #4b5563;
        transition: all 0.2s;
    }

    .sidebar-nav .nav-link:hover {
        background: #f3f4f6;
        color: #1f2937;
    }

    .sidebar-nav .nav-link.active {
        background: #eef2ff;
        color: #4338ca;
        font-weight: 600;
    }

    .sidebar-tips {
        background: linear-gradient(to bottom right, #4f46e5, #7c3aed);
        border-radius: 1.25rem;
        padding: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .sidebar-tips .tips-icon {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.1;
    }
</style>

<div class="py-4">
    <div class="container maxWidth6xl">
        <!-- Premium Header -->
        <div class="profile-banner mb-4">
            <div class="row align-items-center">
                <div class="col-md-auto text-center mb-3 mb-md-0">
                    <div class="avatar-wrapper mx-auto">
                        @if($user->photo_url)
                            <img src="{{ $user->photo_url }}" alt="Profile Photo" class="avatar-img">
                        @else
                            <div class="avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <label for="photo_upload" class="camera-btn">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                </div>
                <div class="col text-center text-md-start">
                    <h1 class="display-5 fw-bold mb-2">{{ $user->name }}</h1>
                    <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2">
                        <span class="badge-premium">
                            <i class="fas fa-user-shield me-2"></i> {{ ucfirst($user->role ?? 'Administrator') }}
                        </span>
                        <span class="badge-premium" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.1);">
                            <i class="fas fa-envelope me-2"></i> {{ $user->email }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="premium-card p-3 mb-4">
                    <div class="sidebar-nav">
                        <a href="#personal-info" class="nav-link active">
                            <i class="fas fa-id-card me-2"></i> Informasi Personal
                        </a>
                        <a href="#security" class="nav-link">
                            <i class="fas fa-shield-alt me-2"></i> Keamanan & Password
                        </a>
                        <hr class="my-2 opacity-10">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link text-danger fw-bold">
                            <i class="fas fa-arrow-left me-2"></i> Dashboard Admin
                        </a>
                    </div>
                </div>

                <div class="sidebar-tips mb-4 shadow-sm">
                    <i class="fas fa-shield-alt tips-icon"></i>
                    <h5 class="fw-bold mb-3">Keamanan Akun</h5>
                    <p class="small mb-0 opacity-75">
                        Gunakan password yang kuat (minimal 8 karakter dengan kombinasi huruf besar, kecil, dan simbol) untuk menjaga keamanan akun Anda.
                    </p>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-8">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-check-circle me-3"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error') || $errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-exclamation-triangle me-3"></i>
                            <div class="fw-bold">{{ session('error') ?? 'Terdapat kesalahan input:' }}</div>
                        </div>
                        @if($errors->any())
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <!-- Personal Info Card -->
                <div id="personal-info" class="premium-card">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2 text-primary"></i> Data Diri</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="file" name="photo" id="photo_upload" class="d-none" onchange="this.form.submit()">

                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Alamat Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" disabled>
                                    </div>
                                    <div class="form-text xsmall text-muted">Email tidak dapat diubah</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIP</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                        <input type="text" name="nip" class="form-control" value="{{ old('nip', $user->nip) }}" placeholder="Nomor Induk Pegawai">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jabatan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                        <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $user->jabatan) }}" placeholder="Contoh: Admin Akademik">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4 py-2 border-radius-lg fw-bold shadow-sm">
                                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Card -->
                <div id="security" class="premium-card">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-key me-2 text-warning"></i> Ganti Password</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.profile.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Password Saat Ini</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                        <input type="password" name="new_password" class="form-control" required placeholder="Min. 8 karakter">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-check-shield"></i></span>
                                        <input type="password" name="new_password_confirmation" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <div class="text-end">
                                <button type="submit" class="btn btn-dark px-4 py-2 border-radius-lg fw-bold shadow-sm">
                                    <i class="fas fa-sync-alt me-2"></i> Perbarui Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style nonce="{{ $csp_nonce ?? '' }}">
    .maxWidth6xl { max-width: 1140px; margin: 0 auto; }
</style>
@endsection
