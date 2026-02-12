@extends('layouts.app')

@section('title', 'Pendaftaran TOEFL')

@section('content')
    <style nonce="{{ $csp_nonce ?? '' }}">
        @keyframes shakeX {

            from,
            to {
                transform: translate3d(0, 0, 0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translate3d(-10px, 0, 0);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translate3d(10px, 0, 0);
            }
        }

        .animate__shakeX {
            animation-name: shakeX;
            animation-duration: 0.5s;
        }

        .animate__animated {
            animation-fill-mode: both;
        }

        .error-card {
            border-left: 5px solid #dc3545;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: slideInDown 0.5s;
        }

        @keyframes slideInDown {
            from {
                transform: translate3d(0, -100%, 0);
                visibility: visible;
            }

            to {
                transform: translate3d(0, 0, 0);
            }
        }

        .review-field {
            transition: all 0.3s ease;
        }

        .review-field:hover {
            padding-left: 5px;
            color: #0d6efd !important;
        }

        .review-thumb-container {
            width: 100% !important;
            height: 150px !important;
            border: 2px solid #dee2e6 !important;
            border-radius: 10px !important;
            overflow: hidden !important;
            background-color: #f8f9fa !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin-bottom: 10px !important;
            position: relative !important;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05) !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
        }

        .review-thumb-container:hover {
            border-color: #0d6efd !important;
            box-shadow: 0 0 15px rgba(13, 110, 253, 0.2) !important;
            transform: translateY(-2px);
        }

        .review-thumb-container:hover img {
            transform: scale(1.05);
        }

        .review-thumb-container img {
            max-width: 100% !important;
            max-height: 100% !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
            display: block !important;
            transition: transform 0.3s ease !important;
        }

        .review-thumb-container i {
            color: #dee2e6;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Formulir Pendaftaran TOEFL</h1>
                <p class="lead">Silakan isi semua kolom yang wajib untuk mendaftar tes TOEFL</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger error-card p-4 mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                            <h5 class="mb-0 fw-bold">Ups! Ada Kesalahan Input</h5>
                        </div>
                        <ul class="mb-0 ms-4 ps-1">
                            @foreach($errors->all() as $error)
                                <li class="py-1">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Formulir Pendaftaran</h5>
                    </div>
                    <div class="card-body">
                        <!-- Multi-step form -->
                        <form action="{{ route('participant.register') }}" method="POST" enctype="multipart/form-data"
                            id="registrationForm">
                            @csrf
                            <!-- Progress bar -->
                            <div class="progress mb-4">
                                <div class="progress-bar" id="progressBar" role="progressbar" style="width: 20%"
                                    aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <!-- Step 1: Basic Information -->
                            <div id="step1" class="form-step">
                                <h4 class="mb-3">Informasi Dasar</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="schedule_id" class="form-label">Pilih Jadwal Tes <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="schedule_id" name="schedule_id" required>
                                                <option value="">Pilih jadwal</option>
                                                @foreach($schedules as $schedule)
                                                    <option value="{{ $schedule->id }}"
                                                        data-capacity="{{ $schedule->capacity }}"
                                                        data-used="{{ $schedule->used_capacity }}">
                                                        {{ $schedule->date->format('d M Y') }} - {{ $schedule->time }} -
                                                        Ruangan: {{ $schedule->room }}
                                                        ({{ $schedule->used_capacity }}/{{ $schedule->capacity }} terisi)
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text">Pilih jadwal tes TOEFL yang tersedia</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nim" class="form-label">NIM (Nomor Induk Mahasiswa) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nim') is-invalid @enderror"
                                                id="nim" name="nim" value="{{ old('nim') }}" required
                                                placeholder="Contoh: H1C022001" maxlength="20">
                                            @error('nim')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Masukkan NIM Anda yang aktif</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Lengkap <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso">
                                            <div class="form-text">Nama lengkap sesuai dengan identitas resmi</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="gender" class="form-label">Jenis Kelamin <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="gender" name="gender" required>
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki
                                                </option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                    Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="birth_place" class="form-label">Tempat Lahir <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="birth_place" name="birth_place"
                                                value="{{ old('birth_place') }}" required placeholder="Contoh: Kendari">
                                            <div class="form-text">Tempat lahir sesuai dengan identitas resmi</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="birth_date" class="form-label">Tanggal Lahir <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="birth_date" name="birth_date"
                                                value="{{ old('birth_date') }}" required>
                                            <div class="form-text">Pilih tanggal lahir Anda</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ old('email') }}" required placeholder="contoh@email.com">
                                            <div class="form-text">Email aktif yang dapat dihubungi</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Nomor WhatsApp Aktif <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                value="{{ old('phone') }}" required placeholder="Contoh: 081234567890">
                                            <div class="form-text">Nomor WhatsApp aktif untuk komunikasi</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <div></div> <!-- Empty div for spacing -->
                                    <button type="button" class="btn btn-primary btn-next" data-step="1">Lanjut ke Informasi
                                        Akademik</button>
                                </div>
                            </div>

                            <!-- Step 2: Academic Information -->
                            <div id="step2" class="form-step" style="display: none;">
                                <h4 class="mb-3">Informasi Akademik</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="faculty_id" class="form-label">Fakultas <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="faculty_id" name="faculty_id" required>
                                                <option value="">Pilih Fakultas</option>
                                                @foreach($faculties as $faculty)
                                                    <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                                        {{ $faculty->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('faculty_id')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Pilih fakultas Anda</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="study_program_id" class="form-label">Program Studi <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="study_program_id" name="study_program_id"
                                                required>
                                                <option value="">Pilih Program Studi</option>
                                                @foreach($studyPrograms as $program)
                                                    <option value="{{ $program->id }}" {{ old('study_program_id') == $program->id ? 'selected' : '' }} data-faculty-id="{{ $program->faculty_id }}">
                                                        {{ $program->name }} ({{ $program->level }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('study_program_id')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Pilih program studi Anda</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label d-block">Waktu Pembayaran (Sesuai Slip) <span
                                                    class="text-danger">*</span></label>
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <input type="date" class="form-control" id="payment_date"
                                                        name="payment_date" value="{{ old('payment_date') }}" required>
                                                    <div class="form-text">Tanggal pembayaran</div>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-select" name="payment_hour" required>
                                                        <option value="">Jam</option>
                                                        @for($i = 0; $i < 24; $i++)
                                                            <option value="{{ sprintf('%02d', $i) }}" {{ old('payment_hour') == sprintf('%02d', $i) ? 'selected' : '' }}>
                                                                {{ sprintf('%02d', $i) }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-select" name="payment_minute" required>
                                                        <option value="">Menit</option>
                                                        @for($i = 0; $i < 60; $i++)
                                                            <option value="{{ sprintf('%02d', $i) }}" {{ old('payment_minute') == sprintf('%02d', $i) ? 'selected' : '' }}>
                                                                {{ sprintf('%02d', $i) }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-select" name="payment_second" required>
                                                        <option value="">Detik</option>
                                                        @for($i = 0; $i < 60; $i++)
                                                            <option value="{{ sprintf('%02d', $i) }}" {{ old('payment_second') == sprintf('%02d', $i) ? 'selected' : '' }}>
                                                                {{ sprintf('%02d', $i) }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-text">Masukkan Tanggal, Jam, Menit, dan Detik persis seperti
                                                yang tertera pada struk/bukti transfer Anda.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="test_category" class="form-label">Kategori Tes <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="test_category" name="test_category" required>
                                                <option value="">Pilih Kategori Tes</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category }}" {{ old('test_category') == $category ? 'selected' : '' }}>
                                                        {{ $category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text">Pilih kategori tes yang akan diikuti</div>
                                        </div>
                                    </div>
                                </div>


                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-prev" data-step="2">Kembali</button>
                                    <button type="button" class="btn btn-primary btn-next" data-step="2">Lanjut ke
                                        Dokumen</button>
                                </div>
                            </div>

                            <!-- Step 3: Document Uploads -->
                            <div id="step3" class="form-step" style="display: none;">
                                <h4 class="mb-3">Unggah Dokumen yang Diperlukan</h4>
                                <p class="text-muted">Harap unggah dokumen-dokumen berikut dalam format JPG atau PNG
                                    (maksimal 1MB per file)</p>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="payment_proof" class="form-label">Bukti Pembayaran <span
                                                    class="text-danger">*</span></label>
                                            <input type="file"
                                                class="form-control @error('payment_proof') is-invalid @enderror"
                                                id="payment_proof" name="payment_proof"
                                                accept="image/jpeg,image/jpg,image/png" required>
                                            @error('payment_proof')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Hanya File JPG, PNG (maks 1MB)</div>
                                            <div id="payment_proof_preview" class="mt-2"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="photo" class="form-label">Foto <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                                id="photo" name="photo" accept="image/*" required>
                                            @error('photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Hanya File JPG, PNG (maks 1MB)</div>
                                            <div id="photo_preview" class="mt-2"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="ktp" class="form-label">KTP <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" class="form-control @error('ktp') is-invalid @enderror"
                                                id="ktp" name="ktp" accept="image/jpeg,image/jpg,image/png" required>
                                            @error('ktp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Hanya File JPG, PNG (maks 1MB)</div>
                                            <div id="ktp_preview" class="mt-2"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-prev" data-step="3">Kembali</button>
                                    <button type="button" class="btn btn-primary btn-next" data-step="3">Lanjut ke
                                        Akun</button>
                                </div>
                            </div>

                            <!-- Step 4: Account Information -->
                            <div id="step4" class="form-step" style="display: none;">
                                <h4 class="mb-3">Informasi Akun</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Nama Pengguna (User Name) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                                id="username" name="username" value="{{ old('username') }}" required
                                                placeholder="Contoh: budi_santoso">
                                            <div class="form-text">Hanya boleh berisi huruf kecil, angka, dan underscore.
                                                (Dilarang menggunakan spasi atau titik). Contoh: jay_idzes</div>
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Kata Sandi <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password" required
                                                    placeholder="Kata sandi minimal 12 karakter">
                                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Password minimal harus 12 karakter. Password harus
                                                mengandung huruf besar, huruf kecil, angka, dan karakter khusus (@$!%*?&).
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                                id="generatePasswordBtn">
                                                <i class="fas fa-key me-1"></i> Buat Password Otomatis
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi
                                                <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="password_confirmation"
                                                    name="password_confirmation" required placeholder="Ulangi kata sandi">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="toggleConfirmPassword">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">Ketik ulang kata sandi yang sama</div>
                                            <div id="password-match-feedback" class="small mt-1 collapse"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-prev" data-step="4">Kembali</button>
                                    <button type="button" class="btn btn-primary btn-next" data-step="4">Lanjut ke
                                        Review</button>
                                </div>
                            </div>

                            <!-- Step 5: Review & Confirmation -->
                            <div id="step5" class="form-step" style="display: none;">
                                <h4 class="mb-3 text-primary"><i class="fas fa-check-circle me-2"></i>Review & Konfirmasi
                                </h4>
                                <p class="text-muted mb-4">Harap periksa kembali semua data Anda sebelum menekan tombol
                                    Daftar Sekarang.</p>

                                <!-- Review Content -->
                                <div class="review-section mb-4">
                                    <div class="card bg-light border-0 rounded-4 p-4 shadow-sm mb-4">
                                        <h5 class="fw-bold border-bottom pb-2 mb-3"><i
                                                class="fas fa-user me-2"></i>Informasi Dasar</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Nama Lengkap</p>
                                                <p class="fw-bold mb-0 text-dark review-field" data-source="name">-</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">NIM</p>
                                                <p class="fw-bold mb-0 text-dark review-field" data-source="nim">-</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Jadwal Tes</p>
                                                <p class="fw-bold mb-0 text-dark review-field" data-source="schedule_id">-
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Jenis Kelamin</p>
                                                <p class="fw-bold mb-0 text-dark review-field" data-source="gender">-</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Tempat, Tanggal Lahir</p>
                                                <p class="fw-bold mb-0 text-dark review-field" data-source="birth_info">-
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Email</p>
                                                <p class="fw-bold mb-0 text-dark review-field" data-source="email">-</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Nomor WhatsApp</p>
                                                <p class="fw-bold mb-0 text-dark review-field" data-source="phone">-</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card bg-light border-0 rounded-4 p-4 shadow-sm mb-4">
                                        <h5 class="fw-bold border-bottom pb-2 mb-3"><i
                                                class="fas fa-graduation-cap me-2"></i>Informasi Akademik</h5>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <p class="mb-1 text-muted small">Fakultas / Program Studi</p>
                                                <p class="fw-bold mb-0 text-dark review-field" data-source="academic_info">-
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Waktu Pembayaran (Slip)</p>
                                                <p class="fw-bold mb-0 text-dark review-field" data-source="payment_time">-
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Kategori Tes</p>
                                                <p class="fw-bold mb-0 text-dark review-field" data-source="test_category">-
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card bg-light border-0 rounded-4 p-4 shadow-sm mb-4">
                                        <h5 class="fw-bold border-bottom pb-2 mb-3"><i
                                                class="fas fa-file-alt me-2"></i>Dokumen Unggahan</h5>
                                        <div class="row g-4">
                                            <div class="col-md-12">
                                                <p class="mb-3 text-muted small"><i class="fas fa-camera me-1"></i>Klik
                                                    gambar untuk melihat pratinjau penuh</p>
                                                <div class="row g-3">
                                                    <div class="col-4">
                                                        <div class="review-thumb-container" id="review_payment_proof_thumb"
                                                            title="Klik untuk perbesar">
                                                            <i class="fas fa-file-invoice-dollar fa-2x text-light"></i>
                                                        </div>
                                                        <div class="text-center">
                                                            <span class="badge bg-primary rounded-pill">Bukti Bayar</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="review-thumb-container" id="review_photo_thumb"
                                                            title="Klik untuk perbesar">
                                                            <i class="fas fa-user-circle fa-2x text-light"></i>
                                                        </div>
                                                        <div class="text-center">
                                                            <span class="badge bg-primary rounded-pill">Foto</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="review-thumb-container" id="review_ktp_thumb"
                                                            title="Klik untuk perbesar">
                                                            <i class="fas fa-id-card fa-2x text-light"></i>
                                                        </div>
                                                        <div class="text-center">
                                                            <span class="badge bg-primary rounded-pill">KTP</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card bg-light border-0 rounded-4 p-4 shadow-sm mb-4">
                                        <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="fas fa-key me-2"></i>Informasi
                                            Akun</h5>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <p class="mb-1 text-muted small">Nama Pengguna (Username)</p>
                                                <p class="fw-bold mb-0 text-dark review-field text-primary"
                                                    data-source="username">-</p>
                                                <p class="small text-muted mt-2"><i
                                                        class="fas fa-info-circle me-1"></i>Gunakan username ini untuk masuk
                                                    ke dashboard setelah pendaftaran berhasil.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Disclaimer Checkbox -->
                                <div class="card border-warning bg-warning bg-opacity-10 rounded-4 p-4 mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="disclaimerCheckbox" required>
                                        <label class="form-check-label fw-bold text-danger fs-6" for="disclaimerCheckbox"
                                            style="line-height:1.5;">
                                            Informasi ini saya isi dengan sebenar-benarnya sesuai data asli. Saya bersedia
                                            menanggung risiko sepenuhnya atas kesalahan informasi yang saya berikan
                                            tanpa melibatkan atau menuntut pihak UPA Bahasa Universitas Halu Oleo.
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-prev" data-step="5">Kembali</button>
                                    <button type="submit" class="btn btn-success btn-lg px-4" id="finalSubmitBtn" disabled>
                                        <i class="fas fa-paper-plane me-2"></i>Daftar Sekarang
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script nonce="{{ $csp_nonce ?? '' }}">

        document.addEventListener('DOMContentLoaded', function () {
            // Attach event listeners for Next/Prev buttons (CSP Compliant)
            document.querySelectorAll('.btn-next').forEach(button => {
                button.addEventListener('click', function () {
                    const step = parseInt(this.getAttribute('data-step'));
                    nextStep(step);
                });
            });

            document.querySelectorAll('.btn-prev').forEach(button => {
                button.addEventListener('click', function () {
                    const step = parseInt(this.getAttribute('data-step'));
                    prevStep(step);
                });
            });

            // Initialize the first step
            showStep(1);

            // Add real-time file validation
            const fileInputs = document.querySelectorAll('input[type="image"], input[type="file"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const fileSize = file.size / 1024 / 1024; // MB
                        const allowedExtensions = ['jpg', 'jpeg', 'png'];
                        const fileExtension = file.name.split('.').pop().toLowerCase();

                        // Clear existing error
                        this.classList.remove('is-invalid');
                        const existingError = this.parentNode.querySelector('.invalid-feedback');
                        if (existingError) existingError.remove();

                        let errorMsg = '';
                        if (fileSize > 1) {
                            errorMsg = 'Ukuran file terlalu besar (' + fileSize.toFixed(2) + 'MB). Maksimal 1MB.';
                        } else if (!allowedExtensions.includes(fileExtension)) {
                            errorMsg = 'Format file tidak didukung. Gunakan JPG atau PNG.';
                        }

                        if (errorMsg) {
                            this.classList.add('is-invalid');
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback';
                            errorDiv.textContent = errorMsg;
                            this.parentNode.appendChild(errorDiv);
                        }
                    }
                });
            });
        });

        // Multi-step form functionality
        let currentStep = 1;
        const totalSteps = 5;

        function updateProgressBar() {
            const progressPercentage = (currentStep / totalSteps) * 100;
            document.getElementById('progressBar').style.width = progressPercentage + '%';
            document.getElementById('progressBar').setAttribute('aria-valuenow', progressPercentage);
        }

        function showStep(step) {
            // Hide all steps
            for (let i = 1; i <= totalSteps; i++) {
                document.getElementById('step' + i).style.display = 'none';
            }
            // Show current step
            document.getElementById('step' + step).style.display = 'block';
            currentStep = step;
            updateProgressBar();
        }

        function nextStep(current) {
            if (validateStep(current)) {
                if (current < totalSteps) {
                    if (current + 1 === 5) {
                        populateReview();
                    }
                    showStep(current + 1);
                }
            } else {
                // If validation fails, stay on current step
                showStep(current);
            }
        }

        function prevStep(current) {
            if (current > 1) {
                showStep(current - 1);
            }
        }

        function validateStep(step) {
            let isValid = true;
            const stepElement = document.getElementById('step' + step);
            const requiredFields = stepElement.querySelectorAll('[required]');

            // Clear all previous error states first
            const allFields = document.querySelectorAll('.form-control, .form-select');
            allFields.forEach(field => {
                field.classList.remove('is-invalid');
            });

            // Remove all error messages
            const errorMessages = stepElement.querySelectorAll('.invalid-feedback, #nim-error, #phone-error, #password-confirm-error, #birth-date-error, #payment-date-error');
            errorMessages.forEach(error => error.remove());

            requiredFields.forEach(field => {
                // Check if field is valid
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;

                    // Add error message if field is required
                    if (!field.parentNode.querySelector('.invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Bidang ini wajib diisi.';
                        field.parentNode.appendChild(errorDiv);
                    }
                } else if (field.type === 'email' && !isValidEmail(field.value)) {
                    field.classList.add('is-invalid');
                    isValid = false;

                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'Format email tidak valid.';
                    field.parentNode.appendChild(errorDiv);
                } else if (field.type === 'file' && field.required && !field.files.length) {
                    field.classList.add('is-invalid');
                    isValid = false;

                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'File wajib diunggah.';
                    field.parentNode.appendChild(errorDiv);
                } else if (field.type === 'file' && field.files.length > 0) {
                    const file = field.files[0];
                    const fileSize = file.size / 1024 / 1024; // in MB
                    const allowedExtensions = ['jpg', 'jpeg', 'png'];
                    const fileExtension = file.name.split('.').pop().toLowerCase();

                    if (fileSize > 1) {
                        field.classList.add('is-invalid');
                        isValid = false;
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Ukuran file melebihi kapasitas maksimal (1MB).';
                        field.parentNode.appendChild(errorDiv);
                    } else if (!allowedExtensions.includes(fileExtension)) {
                        field.classList.add('is-invalid');
                        isValid = false;
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Format file tidak didukung. Gunakan JPG atau PNG.';
                        field.parentNode.appendChild(errorDiv);
                    }
                }
            });

            // Special validation for NIM format
            if (step === 1) {
                const nimField = document.getElementById('nim');
                if (nimField.value.trim() && !isValidNim(nimField.value.trim())) {
                    nimField.classList.add('is-invalid');
                    isValid = false;

                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'nim-error';
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'Format NIM tidak valid. Contoh format yang benar: H1C022001';
                    nimField.parentNode.appendChild(errorDiv);
                }
            }

            // Special validation for phone number
            if (step === 1) {
                const phoneField = document.getElementById('phone');
                if (phoneField.value.trim() && !isValidPhone(phoneField.value.trim())) {
                    phoneField.classList.add('is-invalid');
                    isValid = false;

                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'phone-error';
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'Nomor WhatsApp tidak valid. Gunakan format: 081234567890';
                    phoneField.parentNode.appendChild(errorDiv);
                }
            }

            // Special validation for username and password confirmation
            if (step === 4) {
                const username = document.getElementById('username').value;
                if (!validateUsername(username)) {
                    isValid = false;
                }

                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;
                if (password !== confirmPassword) {
                    document.getElementById('password_confirmation').classList.add('is-invalid');
                    isValid = false;

                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'password-confirm-error';
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'Konfirmasi password tidak cocok.';
                    document.getElementById('password_confirmation').parentNode.appendChild(errorDiv);
                }
            }

            // Special validation for disclaimer checkbox (Step 5)
            if (step === 5) {
                const disclaimer = document.getElementById('disclaimerCheckbox');
                if (!disclaimer.checked) {
                    isValid = false;
                    disclaimer.classList.add('is-invalid');
                }
            }

            return isValid;
        }

        function populateReview() {
            // Fill basic fields
            const sources = ['name', 'nim', 'email', 'phone', 'username', 'test_category', 'birth_place'];
            sources.forEach(id => {
                const input = document.getElementById(id);
                const reviewEl = document.querySelector(`.review-field[data-source="${id}"]`);
                if (input && reviewEl) {
                    reviewEl.textContent = input.value || '-';
                }
            });

            // Special: Gender
            const genderInput = document.getElementById('gender');
            const genderReview = document.querySelector('.review-field[data-source="gender"]');
            if (genderInput && genderReview) {
                genderReview.textContent = genderInput.value === 'male' ? 'Laki-laki' : (genderInput.value === 'female' ? 'Perempuan' : '-');
            }

            // Special: Birth Info (Place, Date)
            const bPlace = document.getElementById('birth_place').value;
            const bDate = document.getElementById('birth_date').value;
            const birthReview = document.querySelector('.review-field[data-source="birth_info"]');
            if (birthReview) {
                birthReview.textContent = bPlace && bDate ? `${bPlace}, ${bDate}` : '-';
            }

            // Special: Schedule
            const scheduleSelect = document.getElementById('schedule_id');
            const scheduleReview = document.querySelector('.review-field[data-source="schedule_id"]');
            if (scheduleSelect && scheduleSelect.selectedIndex >= 0) {
                scheduleReview.textContent = scheduleSelect.options[scheduleSelect.selectedIndex].text;
            }

            // Special: Academic Info (Faculty + Study Program)
            const facultySelect = document.getElementById('faculty_id');
            const programSelect = document.getElementById('study_program_id');
            const academicReview = document.querySelector('.review-field[data-source="academic_info"]');
            if (academicReview && facultySelect.selectedIndex >= 0 && programSelect.selectedIndex >= 0) {
                const facultyName = facultySelect.options[facultySelect.selectedIndex].text;
                const programName = programSelect.options[programSelect.selectedIndex].text;
                academicReview.textContent = `${facultyName.trim()} / ${programName.trim()}`;
            }

            // Special: Payment Time
            const pDate = document.getElementById('payment_date').value;
            const pHour = document.querySelector('select[name="payment_hour"]').value;
            const pMin = document.querySelector('select[name="payment_minute"]').value;
            const pSec = document.querySelector('select[name="payment_second"]').value;
            const paymentReview = document.querySelector('.review-field[data-source="payment_time"]');
            if (paymentReview && pDate) {
                paymentReview.textContent = `${pDate} ${pHour}:${pMin}:${pSec}`;
            }

            // Documents check & Thumbnails
            displayReviewThumbnail('payment_proof', 'review_payment_proof_thumb');
            displayReviewThumbnail('photo', 'review_photo_thumb');
            displayReviewThumbnail('ktp', 'review_ktp_thumb');
        }

        function showPreviewModal(src, title) {
            const modalEl = document.getElementById('imagePreviewModal');
            const modalImg = document.getElementById('previewModalImage');
            const modalTitle = document.getElementById('imagePreviewModalLabel');

            if (modalEl && modalImg && modalTitle) {
                modalImg.src = src;
                modalTitle.textContent = title;
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        }

        function displayReviewThumbnail(inputId, targetId) {
            const input = document.getElementById(inputId);
            const target = document.getElementById(targetId);

            let title = "Pratinjau Dokumen";
            if (inputId === 'payment_proof') title = "Pratinjau Bukti Bayar";
            if (inputId === 'photo') title = "Pratinjau Foto";
            if (inputId === 'ktp') title = "Pratinjau KTP";

            if (input && target && input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    target.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    // Make the container clickable for the modal
                    target.onclick = function () {
                        showPreviewModal(e.target.result, title);
                    };
                };
                reader.readAsDataURL(input.files[0]);
            } else if (target) {
                target.innerHTML = '<i class="fas fa-times-circle text-danger fa-2x"></i>';
                target.onclick = null;
            }
        }

        // Disclaimer checkbox listener
        document.addEventListener('change', function (e) {
            if (e.target && e.target.id === 'disclaimerCheckbox') {
                const submitBtn = document.getElementById('finalSubmitBtn');
                if (submitBtn) {
                    submitBtn.disabled = !e.target.checked;
                    if (e.target.checked) {
                        submitBtn.classList.add('animate__animated', 'animate__pulse');
                    } else {
                        submitBtn.classList.remove('animate__animated', 'animate__pulse');
                    }
                }
                if (e.target.checked) {
                    e.target.classList.remove('is-invalid');
                }
            }
        });

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function isValidNim(nim) {
            // Simple validation: at least 5 characters, starts with letter, contains numbers
            const nimRegex = /^[A-Za-z0-9]+$/;
            return nimRegex.test(nim) && nim.length >= 5;
        }

        function isValidPhone(phone) {
            // Simple validation: numbers only and starts with 08
            const phoneRegex = /^08\d{8,}$/;
            return phoneRegex.test(phone);
        }

        // File preview functionality
        function setupFilePreview(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            if (!input || !preview) return;

            input.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    // Check if file is an image
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            preview.innerHTML = '';
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.style.maxWidth = '100%';
                            img.style.maxHeight = '150px';
                            img.style.borderRadius = '4px';
                            img.style.border = '1px solid #ddd';
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // For non-image files, show file info
                        preview.innerHTML = '';
                        const fileInfo = document.createElement('div');
                        fileInfo.innerHTML = `
                                                                    <span class="badge bg-primary">${file.name}</span>
                                                                    <small class="text-muted d-block mt-1">${formatFileSize(file.size)}</small>
                                                                `;
                        preview.appendChild(fileInfo);
                    }
                } else {
                    preview.innerHTML = '';
                }
            });
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }


        // Faculty and Study Program filtering (existing functionality)
        const facultySelect = document.getElementById('faculty_id');
        const studyProgramSelect = document.getElementById('study_program_id');

        function filterStudyPrograms() {
            const selectedFacultyId = facultySelect.value;

            // Show all options first
            for (let option of studyProgramSelect.options) {
                if (option.value !== "") {
                    option.style.display = 'block';
                }
            }

            // Hide options that don't belong to the selected faculty
            if (selectedFacultyId) {
                for (let option of studyProgramSelect.options) {
                    if (option.value !== "" && option.dataset.facultyId !== selectedFacultyId) {
                        option.style.display = 'none';
                    }
                }
            }
        }

        // Add event listener to faculty select
        facultySelect.addEventListener('change', filterStudyPrograms);

        // Initialize the filter if there's a saved value
        if (facultySelect.value) {
            filterStudyPrograms();
        }

        // Username real-time validation (existing functionality)
        const usernameInput = document.getElementById('username');

        function validateUsername(value) {
            // Regex pattern: lowercase letters, numbers, and underscores only
            const regex = /^[a-z0-9_]+$/;

            if (!regex.test(value)) {
                // Mark as invalid and show error message
                usernameInput.classList.add('is-invalid');

                // Remove any existing error message
                let existingError = document.getElementById('username-error');
                if (existingError) {
                    existingError.remove();
                }

                // Create error message
                const errorDiv = document.createElement('div');
                errorDiv.id = 'username-error';
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'Username hanya boleh berisi huruf kecil, angka, dan underscore. (Dilarang menggunakan spasi atau titik).';
                errorDiv.style.display = 'block';

                // Insert after the input element
                usernameInput.parentNode.insertBefore(errorDiv, usernameInput.nextSibling);

                return false;
            } else {
                usernameInput.classList.remove('is-invalid');

                // Remove error message if it exists
                let existingError = document.getElementById('username-error');
                if (existingError) {
                    existingError.remove();
                }

                return true;
            }
        }

        // Convert username input to lowercase in real-time
        if (usernameInput) {
            // Convert to lowercase on input
            usernameInput.addEventListener('input', function () {
                const currentValue = this.value;
                const newValue = currentValue.toLowerCase();

                if (currentValue !== newValue) {
                    this.value = newValue;

                    // Show validation message if invalid characters are detected
                    validateUsername(newValue);
                }
            });

            // Convert to lowercase on blur to ensure it's lowercase
            usernameInput.addEventListener('blur', function () {
                this.value = this.value.toLowerCase();
            });
        }

        // Refresh CSRF token just before form submission to prevent 419 errors
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                // Check if we're on the last step, if not, prevent submission
                if (currentStep !== totalSteps) {
                    e.preventDefault(); // Stop form submission if not on last step
                    showStep(totalSteps); // Go to the last step
                    return false;
                }

                // Validate all steps before submitting - check all required fields in the form
                const allRequiredFields = form.querySelectorAll('[required]');
                let allValid = true;

                allRequiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        allValid = false;
                    } else if (field.type === 'email' && !isValidEmail(field.value)) {
                        allValid = false;
                    } else if (field.type === 'file' && field.required && !field.files.length) {
                        allValid = false;
                    }
                });

                if (!allValid) {
                    e.preventDefault(); // Stop form submission if any required field is invalid
                    showStep(currentStep); // Stay on current step
                    alert('Harap lengkapi semua data yang diperlukan sebelum mendaftar.');
                    return false;
                }

                // Disable submit button to prevent double submission
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = 'Memproses...'; // Change text to indicate processing
                }

                // Update CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const tokenField = form.querySelector('input[name="_token"]');
                if (tokenField) {
                    tokenField.value = csrfToken;
                }
            });
        }

        // Add real-time validation for all fields to highlight errors immediately
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        inputs.forEach(input => {
            // On blur (when user leaves the field), check if empty and show error
            input.addEventListener('blur', function () {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('is-invalid');
                    // Add error message if not already present
                    if (!this.parentNode.querySelector('.invalid-feedback') &&
                        !this.parentNode.querySelector('#nim-error') &&
                        !this.parentNode.querySelector('#phone-error') &&
                        !this.parentNode.querySelector('#password-confirm-error') &&
                        !this.parentNode.querySelector('#username-error')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Bidang ini wajib diisi.';
                        this.parentNode.appendChild(errorDiv);
                    }
                } else {
                    // If field has content, remove error class and message
                    this.classList.remove('is-invalid');
                    const errorDiv = this.parentNode.querySelector('.invalid-feedback');
                    if (errorDiv && errorDiv.textContent === 'Bidang ini wajib diisi.') {
                        errorDiv.remove();
                    }
                }
            });

            // On input (when user types), remove error class if field has content
            input.addEventListener('input', function () {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');

                    // Remove error message if it exists
                    const errorDiv = this.parentNode.querySelector('.invalid-feedback');
                    if (errorDiv && errorDiv.textContent === 'Bidang ini wajib diisi.') {
                        errorDiv.remove();
                    }
                }
            });
        });

        // Add validation for date fields to ensure proper date ranges
        const birthDateInput = document.getElementById('birth_date');
        if (birthDateInput) {
            birthDateInput.addEventListener('change', function () {
                const selectedDate = new Date(this.value);
                const today = new Date();
                const minDate = new Date();
                minDate.setFullYear(minDate.getFullYear() - 100); // Allow dates up to 100 years ago

                if (selectedDate > today) {
                    this.classList.add('is-invalid');
                    // Remove existing error message
                    let existingError = this.parentNode.querySelector('#birth-date-error');
                    if (existingError) existingError.remove();

                    // Create error message
                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'birth-date-error';
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'Tanggal lahir tidak boleh lebih dari hari ini.';
                    this.parentNode.appendChild(errorDiv);
                } else if (selectedDate < minDate) {
                    this.classList.add('is-invalid');
                    // Remove existing error
                    let existingError = this.parentNode.querySelector('#birth-date-error');
                    if (existingError) existingError.remove();

                    // Create error message
                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'birth-date-error';
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'Tanggal lahir terlalu lama (maksimal 100 tahun yang lalu).';
                    this.parentNode.appendChild(errorDiv);
                } else {
                    this.classList.remove('is-invalid');
                    let existingError = this.parentNode.querySelector('#birth-date-error');
                    if (existingError) existingError.remove();
                }
            });
        }

        // Add validation for payment date
        const paymentDateInput = document.getElementById('payment_date');
        if (paymentDateInput) {
            paymentDateInput.addEventListener('change', function () {
                const selectedDate = new Date(this.value);
                const today = new Date();

                if (selectedDate > today) {
                    this.classList.add('is-invalid');
                    // Remove existing error
                    let existingError = this.parentNode.querySelector('#payment-date-error');
                    if (existingError) existingError.remove();

                    // Create error message
                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'payment-date-error';
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'Tanggal pembayaran tidak boleh lebih dari hari ini.';
                    this.parentNode.appendChild(errorDiv);
                } else {
                    this.classList.remove('is-invalid');
                    let existingError = this.parentNode.querySelector('#payment-date-error');
                    if (existingError) existingError.remove();
                }
            });
        }

        // Re-enable submit button if form is loaded with errors (meaning submission failed)
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const submitButton = form ? form.querySelector('button[type="submit"]') : null;

            // Check if there are any error messages on the page
            const hasErrors = document.querySelector('.alert-danger') || document.querySelector('.is-invalid');


            if (submitButton && hasErrors) {
                // Re-enable the button and restore original text if there were errors
                submitButton.disabled = false;
                submitButton.innerHTML = 'Daftar Sekarang';
            }
        });

    </script>

    <!-- Payment Error Modal -->
    <div class="modal fade" id="paymentErrorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-circle fa-4x text-danger"></i>
                    </div>
                    <h4 class="fw-bold mb-3 text-danger">Slip Pembayaran Sudah Digunakan!</h4>
                    <p class="text-muted mb-4" id="paymentErrorMessage"></p>
                    <button type="button" class="btn btn-primary px-5 py-2 rounded-pill"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script nonce="{{ $csp_nonce ?? '' }}">
        @if(session('payment_error'))
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('paymentErrorModal'));
                document.getElementById('paymentErrorMessage').textContent = '{{ session('payment_error') }}';
                modal.show();
            });
        @endif
    </script>

    <script nonce="{{ $csp_nonce ?? '' }}">
        document.addEventListener('DOMContentLoaded', function () {
            // Password visibility toggles
            function setupPasswordToggle(inputId, toggleId) {
                const toggleBtn = document.getElementById(toggleId);
                const inputField = document.getElementById(inputId);

                if (toggleBtn && inputField) {
                    toggleBtn.addEventListener('click', function () {
                        const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
                        inputField.setAttribute('type', type);

                        // Toggle icon
                        const icon = this.querySelector('i');
                        if (type === 'text') {
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    });
                }
            }

            setupPasswordToggle('password', 'togglePassword');
            setupPasswordToggle('password_confirmation', 'toggleConfirmPassword');

            // Password Generator
            const generateBtn = document.getElementById('generatePasswordBtn');
            if (generateBtn) {
                generateBtn.addEventListener('click', function () {
                    const length = 16;
                    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@$!%*?&";
                    let retVal = "";

                    // Ensure at least one of each required type
                    retVal += "ABC"; // Upper
                    retVal += "abc"; // Lower
                    retVal += "123"; // Number
                    retVal += "@$!"; // Special

                    // Fill the rest randomly
                    for (let i = 0, n = charset.length; i < length - 12; ++i) {
                        retVal += charset.charAt(Math.floor(Math.random() * n));
                    }

                    // Shuffle the password
                    retVal = retVal.split('').sort(function () { return 0.5 - Math.random() }).join('');

                    const passwordField = document.getElementById('password');
                    const confirmField = document.getElementById('password_confirmation');

                    if (passwordField && confirmField) {
                        passwordField.value = retVal;
                        confirmField.value = retVal;

                        // Show password so user can save it
                        passwordField.setAttribute('type', 'text');
                        confirmField.setAttribute('type', 'text');

                        // Update icons
                        document.querySelector('#togglePassword i').classList.remove('fa-eye');
                        document.querySelector('#togglePassword i').classList.add('fa-eye-slash');
                        document.querySelector('#toggleConfirmPassword i').classList.remove('fa-eye');
                        document.querySelector('#toggleConfirmPassword i').classList.add('fa-eye-slash');

                        // Trigger validation if needed
                        passwordField.classList.remove('is-invalid');
                        confirmField.classList.remove('is-invalid');

                        // Trigger input event to update match status
                        passwordField.dispatchEvent(new Event('input'));
                    }
                });
            }

            // Password match check
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const matchFeedback = document.getElementById('password-match-feedback');

            if (passwordInput && confirmInput && matchFeedback) {
                function checkPasswordMatch() {
                    if (confirmInput.value === '') {
                        matchFeedback.classList.remove('show');
                        return;
                    }

                    matchFeedback.classList.add('show');
                    if (passwordInput.value === confirmInput.value) {
                        matchFeedback.textContent = 'Password cocok ✓';
                        matchFeedback.classList.remove('text-danger');
                        matchFeedback.classList.add('text-success');
                        confirmInput.classList.remove('is-invalid');
                        confirmInput.classList.add('is-valid');
                    } else {
                        matchFeedback.textContent = 'Password tidak cocok ✕';
                        matchFeedback.classList.remove('text-success');
                        matchFeedback.classList.add('text-danger');
                        confirmInput.classList.remove('is-valid');
                        confirmInput.classList.add('is-invalid');
                    }
                }

                passwordInput.addEventListener('input', checkPasswordMatch);
                confirmInput.addEventListener('input', checkPasswordMatch);
            }
        });
    </script>

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="imagePreviewModalLabel">Pratinjau Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <img id="previewModalImage" src="" alt="Preview Full" class="img-fluid rounded-3 shadow-sm"
                        style="max-height: 75vh; width: auto; object-fit: contain;">
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 text-center d-block">
                    <button type="button" class="btn btn-secondary px-5 py-2 rounded-pill"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection