@extends('layouts.app')

@section('title', 'Login Peserta')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Login Peserta</h4>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('participant.login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Nama Pengguna</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="Masukkan nama pengguna">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('participant.register.form') }}">Belum punya akun? Daftar di sini</a>
                </div>

                <div class="text-center mt-2">
                    <a href="{{ route('home') }}">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection