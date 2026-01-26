@extends('layouts.app')

@section('title', 'Pendaftaran TOEFL')

@section('content')
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
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
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
                    <form action="{{ route('participant.register') }}" method="POST" enctype="multipart/form-data" id="registrationForm">
                        @csrf
                        <!-- Progress bar -->
                        <div class="progress mb-4">
                            <div class="progress-bar" id="progressBar" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <!-- Step 1: Basic Information -->
                        <div id="step1" class="form-step">
                            <h4 class="mb-3">Informasi Dasar</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="schedule_id" class="form-label">Pilih Jadwal Tes <span class="text-danger">*</span></label>
                                        <select class="form-select" id="schedule_id" name="schedule_id" required>
                                            <option value="">Pilih jadwal</option>
                                            @foreach($schedules as $schedule)
                                                <option value="{{ $schedule->id }}" data-capacity="{{ $schedule->capacity }}" data-used="{{ $schedule->used_capacity }}">
                                                    {{ $schedule->date->format('d M Y') }} - {{ $schedule->time }} - Ruangan: {{ $schedule->room }} ({{ $schedule->used_capacity }}/{{ $schedule->capacity }} terisi)
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Pilih jadwal tes TOEFL yang tersedia</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nim" class="form-label">NIM (Nomor Induk Mahasiswa) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim" name="nim" value="{{ old('nim') }}" required
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
                                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required
                                            placeholder="Contoh: Budi Santoso">
                                        <div class="form-text">Nama lengkap sesuai dengan identitas resmi</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-select" id="gender" name="gender" required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="birth_place" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required
                                            placeholder="Contoh: Kendari">
                                        <div class="form-text">Tempat lahir sesuai dengan identitas resmi</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                        <div class="form-text">Pilih tanggal lahir Anda</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required
                                            placeholder="contoh@email.com">
                                        <div class="form-text">Email aktif yang dapat dihubungi</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required
                                            placeholder="Contoh: 081234567890">
                                        <div class="form-text">Nomor WhatsApp aktif untuk komunikasi</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <div></div> <!-- Empty div for spacing -->
                                <button type="button" class="btn btn-primary btn-next" data-step="1">Lanjut ke Informasi Akademik</button>
                            </div>
                        </div>

                        <!-- Step 2: Academic Information -->
                        <div id="step2" class="form-step" style="display: none;">
                            <h4 class="mb-3">Informasi Akademik</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="faculty_id" class="form-label">Fakultas <span class="text-danger">*</span></label>
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
                                        <label for="study_program_id" class="form-label">Program Studi <span class="text-danger">*</span></label>
                                        <select class="form-select" id="study_program_id" name="study_program_id" required>
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
                                         <label class="form-label d-block">Waktu Pembayaran (Sesuai Slip) <span class="text-danger">*</span></label>
                                         <div class="row g-2">
                                             <div class="col-md-6">
                                                 <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ old('payment_date') }}" required>
                                                 <div class="form-text">Tanggal pembayaran</div>
                                             </div>
                                             <div class="col-md-2">
                                                 <select class="form-select" name="payment_hour" required>
                                                     <option value="">Jam</option>
                                                     @for($i = 0; $i < 24; $i++)
                                                         <option value="{{ sprintf('%02d', $i) }}" {{ old('payment_hour') == sprintf('%02d', $i) ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                                     @endfor
                                                 </select>
                                             </div>
                                             <div class="col-md-2">
                                                 <select class="form-select" name="payment_minute" required>
                                                     <option value="">Menit</option>
                                                     @for($i = 0; $i < 60; $i++)
                                                         <option value="{{ sprintf('%02d', $i) }}" {{ old('payment_minute') == sprintf('%02d', $i) ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                                     @endfor
                                                 </select>
                                             </div>
                                             <div class="col-md-2">
                                                 <select class="form-select" name="payment_second" required>
                                                     <option value="">Detik</option>
                                                     @for($i = 0; $i < 60; $i++)
                                                         <option value="{{ sprintf('%02d', $i) }}" {{ old('payment_second') == sprintf('%02d', $i) ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                                     @endfor
                                                 </select>
                                             </div>
                                         </div>
                                         <div class="form-text">Masukkan Tanggal, Jam, Menit, dan Detik persis seperti yang tertera pada struk/bukti transfer Anda.</div>
                                     </div>
                                 </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="test_category" class="form-label">Kategori Tes <span class="text-danger">*</span></label>
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
                                <button type="button" class="btn btn-primary btn-next" data-step="2">Lanjut ke Dokumen</button>
                            </div>
                        </div>

                        <!-- Step 3: Document Uploads -->
                        <div id="step3" class="form-step" style="display: none;">
                            <h4 class="mb-3">Unggah Dokumen yang Diperlukan</h4>
                            <p class="text-muted">Harap unggah dokumen-dokumen berikut dalam format JPG atau PNG (maksimal 2MB per file)</p>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="payment_proof" class="form-label">Bukti Pembayaran <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" id="payment_proof" name="payment_proof" accept="image/jpeg,image/jpg,image/png" required>
                                        @error('payment_proof')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Hanya File JPG, PNG (maks 2MB)</div>
                                        <div id="payment_proof_preview" class="mt-2"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="photo" class="form-label">Foto <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*" required>
                                        @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Hanya File JPG, PNG (maks 2MB)</div>
                                        <div id="photo_preview" class="mt-2"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="ktp" class="form-label">KTP <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('ktp') is-invalid @enderror" id="ktp" name="ktp" accept="image/jpeg,image/jpg,image/png" required>
                                        @error('ktp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Hanya File JPG, PNG (maks 2MB)</div>
                                        <div id="ktp_preview" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary btn-prev" data-step="3">Kembali</button>
                                <button type="button" class="btn btn-primary btn-next" data-step="3">Lanjut ke Akun</button>
                            </div>
                        </div>

                        <!-- Step 4: Account Information -->
                        <div id="step4" class="form-step" style="display: none;">
                            <h4 class="mb-3">Informasi Akun</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Nama Pengguna <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required
                                            placeholder="Contoh: budi_santoso">
                                        <div class="form-text">Hanya boleh berisi huruf kecil, angka, underscore, Contoh : jay_idzes</div>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Kata Sandi <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required
                                                placeholder="Kata sandi minimal 12 karakter">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Password minimal harus 12 karakter. Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus (@$!%*?&).</div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="generatePasswordBtn">
                                            <i class="fas fa-key me-1"></i> Buat Password Otomatis
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required
                                                placeholder="Ulangi kata sandi">
                                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
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
                                <button type="submit" class="btn btn-success btn-lg px-4">Daftar Sekarang</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    // Attach event listeners for Next/Prev buttons (CSP Compliant)
    document.querySelectorAll('.btn-next').forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = parseInt(this.getAttribute('data-step'));
            nextStep(currentStep);
        });
    });

    document.querySelectorAll('.btn-prev').forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = parseInt(this.getAttribute('data-step'));
            prevStep(currentStep);
        });
    });

    const facultySelect = document.getElementById('faculty_id');
    const studyProgramSelect = document.getElementById('study_program_id');
    const usernameInput = document.getElementById('username');

    // Function to filter study programs based on selected faculty
    function filterStudyPrograms() {
        const selectedFacultyId = facultySelect.value;

        // Show all options first
        for (let option of studyProgramSelect.options) {
            option.style.display = 'block';
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

    // Initialize the filter
    filterStudyPrograms();

    // Convert username input to lowercase in real-time
    if (usernameInput) {
        // Convert to lowercase on input
        usernameInput.addEventListener('input', function() {
            const currentValue = this.value;
            const newValue = currentValue.toLowerCase();

            if (currentValue !== newValue) {
                this.value = newValue;

                // Show validation message if invalid characters are detected
                validateUsername(newValue);
            }
        });

        // Convert to lowercase on blur to ensure it's lowercase
        usernameInput.addEventListener('blur', function() {
            this.value = this.value.toLowerCase();
        });
    }

    // Username validation function
    function validateUsername(value) {
        // Regex pattern: lowercase letters, numbers, dots, underscores, and hyphens only
        const regex = /^[a-z0-9._%-]+$/;

        if (!regex.test(value)) {
            // Mark as invalid and show error message
            usernameInput.classList.add('is-invalid');
            usernameInput.classList.remove('is-valid');

            // Remove any existing error message
            let existingError = document.getElementById('username-error');
            if (existingError) {
                existingError.remove();
            }

            // Create error message
            const errorDiv = document.createElement('div');
            errorDiv.id = 'username-error';
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = '';
            errorDiv.style.display = 'block';

            // Insert after the input element
            usernameInput.parentNode.insertBefore(errorDiv, usernameInput.nextSibling);

            return false;
        } else {
            usernameInput.classList.remove('is-invalid');
            usernameInput.classList.add('is-valid');

            // Remove error message if it exists
            let existingError = document.getElementById('username-error');
            if (existingError) {
                existingError.remove();
            }

            return true;
        }
    }

    // Refresh CSRF token just before form submission to prevent 419 errors
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Get the latest CSRF token from the meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            // Update the hidden token field
            const tokenField = form.querySelector('input[name="_token"]');
            if (tokenField) {
                tokenField.value = csrfToken;
            }

            // Validate username before submitting
            if (usernameInput) {
                const isValid = validateUsername(usernameInput.value);
                if (!isValid) {
                    e.preventDefault();
                    usernameInput.focus();
                    return false;
                }
            }

            // Comprehensive re-validation of ALL steps before final submission
            let allStepsValid = true;
            for (let i = 1; i <= totalSteps; i++) {
                if (!validateStep(i)) {
                    allStepsValid = false;
                    showStep(i); // Show the first step that contains error
                    e.preventDefault();
                    return false;
                }
            }

            // Cumulative file size check to prevent "413 Content Too Large"
            const fileInputs = form.querySelectorAll('input[type="file"]');
            let totalSizeBytes = 0;
            fileInputs.forEach(input => {
                if (input.files.length > 0) {
                    totalSizeBytes += input.files[0].size;
                }
            });

            const totalSizeMB = totalSizeBytes / 1024 / 1024;
            const maxTotalSizeMB = 5.0; // Stay safe within post_max_size (6M)

            if (totalSizeMB > maxTotalSizeMB) {
                e.preventDefault();
                alert('Total ukuran semua file (' + totalSizeMB.toFixed(2) + ' MB) melebihi batas maksimal yang diizinkan (5 MB). Mohon perkecil ukuran file Anda sebelum mengirim.');
                return false;
            }
        });
    }

    // Add real-time validation for all fields to highlight errors immediately
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    inputs.forEach(input => {
        // On blur (when user leaves the field), check if empty and show error
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
                // Add error message if not already present
                if (!this.parentNode.querySelector('.invalid-feedback')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'Bidang ini wajib diisi.';
                    this.parentNode.appendChild(errorDiv);
                }
            }
        });

        // On input (when user types), remove error class if field has content
        input.addEventListener('input', function() {
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
});

// Multi-step form functionality
let currentStep = 1;
const totalSteps = 4;

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

            if (fileSize > 2) {
                field.classList.add('is-invalid');
                isValid = false;
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'Ukuran file melebihi kapasitas maksimal (2MB).';
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

    // Special validation for password confirmation
    if (step === 4) {
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

    return isValid;
}

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

    input.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Check if file is an image
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
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

// Initialize file previews
setupFilePreview('payment_proof', 'payment_proof_preview');
setupFilePreview('photo', 'photo_preview');
setupFilePreview('ktp', 'ktp_preview');

// Initialize the first step
showStep(1);

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
    // Regex pattern: lowercase letters, numbers, dots, underscores, and hyphens only
    const regex = /^[a-z0-9._%-]+$/;

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
        errorDiv.textContent = 'Username hanya boleh berisi huruf kecil, angka, titik, underscore, dan tanda hubung.';
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
    usernameInput.addEventListener('input', function() {
        const currentValue = this.value;
        const newValue = currentValue.toLowerCase();

        if (currentValue !== newValue) {
            this.value = newValue;

            // Show validation message if invalid characters are detected
            validateUsername(newValue);
        }
    });

    // Convert to lowercase on blur to ensure it's lowercase
    usernameInput.addEventListener('blur', function() {
        this.value = this.value.toLowerCase();
    });
}

// Refresh CSRF token just before form submission to prevent 419 errors
const form = document.querySelector('form');
if (form) {
    form.addEventListener('submit', function(e) {
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
    input.addEventListener('blur', function() {
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
    input.addEventListener('input', function() {
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
    birthDateInput.addEventListener('change', function() {
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
    paymentDateInput.addEventListener('change', function() {
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
document.addEventListener('DOMContentLoaded', function() {
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
                <button type="button" class="btn btn-primary px-5 py-2 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
@if(session('payment_error'))
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('paymentErrorModal'));
        document.getElementById('paymentErrorMessage').textContent = '{{ session('payment_error') }}';
        modal.show();
    });
@endif
</script>

<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggles
    function setupPasswordToggle(inputId, toggleId) {
        const toggleBtn = document.getElementById(toggleId);
        const inputField = document.getElementById(inputId);
        
        if (toggleBtn && inputField) {
            toggleBtn.addEventListener('click', function() {
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
        generateBtn.addEventListener('click', function() {
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
            retVal = retVal.split('').sort(function(){return 0.5-Math.random()}).join('');
            
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

@endsection