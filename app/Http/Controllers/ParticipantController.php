<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Participant;
use App\Models\StudyProgram;
use App\Models\Faculty;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ParticipantController extends Controller
{
    public function showRegistrationForm()
    {
        $schedules = Schedule::available()->get();

        // Get unique categories from available schedules
        $categories = Schedule::available()
            ->select('category')
            ->distinct()
            ->pluck('category');

        // Get all faculties and study programs for dropdowns
        $faculties = \App\Models\Faculty::all();
        $studyPrograms = \App\Models\StudyProgram::with('faculty')->get();

        return view('participant.register', compact('schedules', 'categories', 'faculties', 'studyPrograms'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
            'nim' => 'required|string|max:255',
            'name' => 'required|string|max:255|regex:/^[A-Za-z\s\.\,\'\-]+$/',
            'gender' => 'required|in:male,female',
            'birth_place' => 'required|string|max:255|regex:/^[A-Za-z\s\.\-]+$/',
            'birth_date' => 'required|date',
            'email' => 'required|email|max:255|regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
            'faculty_id' => 'required|exists:faculties,id',
            'study_program_id' => 'required|exists:study_programs,id',
            'phone' => 'required|string|max:255|regex:/^08\d{8,}$/',
            'payment_date' => 'required|date',
            'test_category' => 'required|string|max:255',
            'payment_proof' => [
                'required',
                'file',
                'max:1024', // 1MB max
                function ($attribute, $value, $fail) {
                    // Check file size first (in KB)
                    $fileSizeKB = $value->getSize() / 1024;
                    if ($fileSizeKB > 1024) {
                        $fail('Ukuran file bukti pembayaran melebihi kapasitas maksimal (1MB).');
                        return;
                    }

                    $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                    $mimeType = $value->getMimeType();
                    $extension = strtolower($value->getClientOriginalExtension());

                    if (!in_array($mimeType, $allowedMimes) && !in_array($extension, ['jpg', 'jpeg', 'png'])) {
                        $fail('Kesalahan jenis file yang di-upload. Bukti pembayaran hanya boleh JPG atau PNG.');
                        return;
                    }

                    // Additional check: verify that the file is actually an image
                    $imageInfo = @getimagesize($value->getRealPath());
                    if (!$imageInfo) {
                        $fail('Kesalahan jenis file yang di-upload. File bukti pembayaran harus berupa gambar yang valid (JPG atau PNG).');
                    }
                },
            ],
            'photo' => [
                'required',
                'file',
                'max:1024', // 1MB max
                function ($attribute, $value, $fail) {
                    // Check file size first (in KB)
                    $fileSizeKB = $value->getSize() / 1024;
                    if ($fileSizeKB > 1024) {
                        $fail('Ukuran file foto melebihi kapasitas maksimal (1MB).');
                        return;
                    }

                    $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                    $mimeType = $value->getMimeType();
                    $extension = strtolower($value->getClientOriginalExtension());

                    if (!in_array($mimeType, $allowedMimes) && !in_array($extension, ['jpg', 'jpeg', 'png'])) {
                        $fail('Kesalahan jenis file yang di-upload. Foto hanya boleh JPG atau PNG.');
                        return;
                    }

                    // Additional check: verify that the file is actually an image
                    $imageInfo = @getimagesize($value->getRealPath());
                    if (!$imageInfo) {
                        $fail('Kesalahan jenis file yang di-upload. File foto harus berupa gambar yang valid (JPG atau PNG).');
                    }
                },
            ],
            'ktp' => [
                'required',
                'file',
                'max:1024', // 1MB max
                function ($attribute, $value, $fail) {
                    // Check file size first (in KB)
                    $fileSizeKB = $value->getSize() / 1024;
                    if ($fileSizeKB > 1024) {
                        $fail('Ukuran file KTP melebihi kapasitas maksimal (1MB).');
                        return;
                    }

                    $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                    $mimeType = $value->getMimeType();
                    $extension = strtolower($value->getClientOriginalExtension());

                    if (!in_array($mimeType, $allowedMimes) && !in_array($extension, ['jpg', 'jpeg', 'png'])) {
                        $fail('Kesalahan jenis file yang di-upload. KTP hanya boleh JPG atau PNG.');
                        return;
                    }

                    // Additional check: verify that the file is actually an image
                    $imageInfo = @getimagesize($value->getRealPath());
                    if (!$imageInfo) {
                        $fail('Kesalahan jenis file yang di-upload. File KTP harus berupa gambar yang valid (JPG atau PNG).');
                    }
                },
            ],
            'username' => 'required|string|max:255|regex:/^[a-z0-9_]+$/',
            'password' => [
                'required',
                'string',
                'min:12',  // SECURITY: Increased from 8 to 12
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ],
        ], [
            'email.regex' => 'Format email tidak valid.',
            'phone.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format: 081234567890',
            'username.regex' => 'Username hanya boleh berisi huruf kecil, angka, dan underscore (tanpa spasi atau titik).',
            'username.required' => 'Username tidak boleh kosong.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal harus 12 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus (@$!%*?&).',
            'name.regex' => 'Nama hanya boleh berisi huruf, spasi, titik, koma, tanda petik tunggal, dan tanda hubung.',
            'birth_place.regex' => 'Tempat lahir hanya boleh berisi huruf, spasi, titik, dan tanda hubung.',
            'payment_hour.required' => 'Jam pembayaran wajib dipilih.',
            'payment_minute.required' => 'Menit pembayaran wajib dipilih.',
            'payment_second.required' => 'Detik pembayaran wajib dipilih.',
            'payment_proof.required' => 'Bukti pembayaran wajib diunggah.',
            'payment_proof.max' => 'Ukuran file bukti pembayaran maksimal 1MB.',
            'payment_proof.file' => 'Bukti pembayaran harus berupa file.',
            'photo.required' => 'Foto wajib diunggah.',
            'photo.max' => 'Ukuran file foto maksimal 1MB.',
            'photo.file' => 'Foto harus berupa file.',
            'ktp.required' => 'KTP wajib diunggah.',
            'ktp.max' => 'Ukuran file KTP maksimal 1MB.',
            'ktp.file' => 'KTP harus berupa file.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Harap lengkapi semua data yang diperlukan dengan benar.');
        }

        // Combine payment date and time
        $paymentDateTime = $request->payment_date . ' ' . $request->payment_hour . ':' . $request->payment_minute . ':' . $request->payment_second;

        // Standardize NIM for comparison
        $standardizedNim = strtoupper(trim($request->nim));

        // Detect Duplicate Payment: Check BOTH NIM AND payment timestamp
        // REJECT only if the SAME person (same NIM) uses the SAME payment timestamp
        // ALLOW if different NIM (different person can have same payment time)
        // ALLOW if different payment time (same person can register again with different payment)
        $duplicatePayment = Participant::where('nim', $standardizedNim)
            ->where('payment_date', $paymentDateTime)
            ->first();

        if ($duplicatePayment) {
            return redirect()->back()
                ->withInput()
                ->with('payment_error', 'Slip pembayaran sudah pernah dipakai oleh peserta lain atau Anda sendiri. Silakan masukkan data pembayaran yang valid.');
        }

        // Check for duplicate NIM to prevent data duplication in database
        $standardizedNim = strtoupper($request->nim);

        // Check if this NIM already has an active or completed registration
        // We block if they are 'pending' or 'confirmed', or if they have already 'passed'
        $existingParticipant = Participant::where('nim', $standardizedNim)
            ->where(function ($query) {
                $query->whereIn('status', ['pending', 'confirmed'])
                    ->orWhere('passed', true);
            })
            ->first();

        if ($existingParticipant) {
            $message = 'NIM sudah terdaftar dan memiliki status Aktif atau sudah Lulus.';
            if ($existingParticipant->passed) {
                $message = 'NIM ini sudah dinyatakan LULUS. Sesuai ketentuan, Anda tidak dapat mendaftar kembali.';
            } else {
                $message = 'NIM ini sudah memiliki pendaftaran Aktif (Menunggu atau Terkonfirmasi). Silakan login ke dashboard untuk melihat status Anda.';
            }

            return redirect()->back()
                ->withErrors(['nim' => $message])
                ->withInput()
                ->with('error', 'NIM sudah terdaftar.');
        }

        // Check if study program belongs to selected faculty
        $studyProgram = StudyProgram::findOrFail($request->study_program_id);
        if ($studyProgram->faculty_id != $request->faculty_id) {
            return redirect()->back()
                ->withErrors(['study_program_id' => 'Program studi tidak sesuai dengan fakultas yang dipilih.'])
                ->withInput()
                ->with('error', 'Program studi tidak sesuai dengan fakultas yang dipilih.');
        }

        $schedule = Schedule::findOrFail($request->schedule_id);

        // Check if schedule is full
        if ($schedule->isFull()) {
            return redirect()->back()
                ->withErrors(['schedule_id' => 'Jadwal yang dipilih sudah penuh. Silakan pilih jadwal lain.'])
                ->withInput()
                ->with('error', 'Jadwal yang dipilih sudah penuh.');
        }

        // Handle file uploads
        $paymentProofPath = null;
        $photoPath = null;
        $ktpPath = null;

        try {
            if ($request->hasFile('payment_proof')) {
                // SECURITY: Store in private storage, not publicly accessible
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'private');

                // Check if payment proof has been used before
                $existingParticipant = Participant::where('payment_proof_path', $paymentProofPath)->first();
                if ($existingParticipant) {
                    \Storage::disk('private')->delete($paymentProofPath); // Delete the uploaded file
                    return redirect()->back()
                        ->withErrors(['payment_proof' => 'Bukti pembayaran sudah pernah digunakan. Silakan unggah bukti pembayaran yang baru.'])
                        ->withInput()
                        ->with('error', 'Bukti pembayaran sudah pernah digunakan.');
                }
            } else {
                return redirect()->back()
                    ->withErrors(['payment_proof' => 'Bukti pembayaran wajib diunggah.'])
                    ->withInput()
                    ->with('error', 'Bukti pembayaran wajib diunggah.');
            }

            if ($request->hasFile('photo')) {
                // SECURITY: Store in private storage
                $photoPath = $request->file('photo')->store('photos', 'private');
            } else {
                return redirect()->back()
                    ->withErrors(['photo' => 'Foto wajib diunggah.'])
                    ->withInput()
                    ->with('error', 'Foto wajib diunggah.');
            }

            if ($request->hasFile('ktp')) {
                // SECURITY: Store in private storage
                $ktpPath = $request->file('ktp')->store('ktps', 'private');
            } else {
                return redirect()->back()
                    ->withErrors(['ktp' => 'KTP wajib diunggah.'])
                    ->withInput()
                    ->with('error', 'KTP wajib diunggah.');
            }

            // Generate temporary seat number (not yet assigned until admin verifies)
            $tempSeatNumber = $this->generateSeatNumber($schedule->room, 'TBA'); // TBA = To Be Assigned

            // Sanitize and transform input data
            $nim = strtoupper(trim(strip_tags($request->nim)));           // Sanitize and convert NIM to uppercase
            $name = strtoupper(trim(strip_tags($request->name)));         // Sanitize and convert name to uppercase
            $birthPlace = strtoupper(trim(strip_tags($request->birth_place)));  // Sanitize and convert birth place to uppercase
            $username = strtolower(trim(strip_tags($request->username))); // Sanitize and convert username to lowercase
            $email = strtolower(trim(strip_tags($request->email)));       // Sanitize and ensure email is lowercase
            $phone = trim(strip_tags($request->phone));                   // Sanitize phone number
            $major = trim(strip_tags($request->major ?? ''));             // Sanitize major if provided
            $faculty = trim(strip_tags($request->faculty ?? ''));         // Sanitize faculty if provided
            $testCategory = trim(strip_tags($request->test_category));    // Sanitize test category

            // Create participant with reserved seat status
            $participant = Participant::create([
                'schedule_id' => $request->schedule_id,
                'seat_number' => 'TBA', // Set to 'To Be Assigned' initially since seat is not yet assigned
                'temp_seat_number' => $tempSeatNumber,
                'seat_status' => 'reserved', // Reserved until admin verifies payment
                'status' => 'pending', // Change from confirmed to pending until admin verifies
                'nim' => $nim,
                'name' => $name,
                'gender' => $request->gender,
                'birth_place' => $birthPlace,
                'birth_date' => $request->birth_date,
                'email' => $email,
                'major' => $studyProgram->name,  // Store major name from study program
                'faculty' => $studyProgram->faculty->name,  // Store faculty name from faculty model
                'phone' => $request->phone,
                'payment_date' => $paymentDateTime,
                'test_category' => $request->test_category,
                'payment_proof_path' => $paymentProofPath,
                'photo_path' => $photoPath,
                'ktp_path' => $ktpPath,
                'study_program_id' => $request->study_program_id,
                'faculty_id' => $request->faculty_id,
                'username' => $username,
                'password' => Hash::make($request->password),
                'verification_token' => (string) Str::uuid(),
            ]);

            // Increment schedule used capacity
            $schedule->increment('used_capacity');

            // Check if schedule is now full
            if ($schedule->used_capacity >= $schedule->capacity) {
                $schedule->update(['status' => 'full']);
            }

            // Log the participant in by setting the session
            session(['participant_id' => $participant->id]);

            // Log activity
            ActivityLogger::log('Registrasi Peserta', 'Peserta baru ' . $participant->name . ' (NIM: ' . $participant->nim . ') mendaftar tes TOEFL.');

            // Return to the dashboard with success message that includes account details
            return redirect()->route('participant.dashboard', ['id' => $participant->id])
                ->with([
                    'success' => 'Pendaftaran Berhasil! Akun Anda telah dibuat. Data pendaftaran Anda sedang menunggu verifikasi dari admin. Bukti pembayaran Anda sedang diverifikasi. Nomor kursi akan ditentukan setelah pembayaran dikonfirmasi oleh admin.',
                    'account_details' => [
                        'username' => $username,
                        'password' => $request->password, // This is the original password entered by user
                        'message' => 'Akun Anda telah dibuat. Silakan simpan username dan password Anda. Tunggu konfirmasi dari petugas UPA Bahasa Universitas Halu Oleo.'
                    ]
                ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Hapus file yang sudah diupload jika terjadi kesalahan
            if ($paymentProofPath && \Storage::disk('private')->exists($paymentProofPath)) {
                \Storage::disk('private')->delete($paymentProofPath);
            }
            if ($photoPath && \Storage::disk('private')->exists($photoPath)) {
                \Storage::disk('private')->delete($photoPath);
            }
            if ($ktpPath && \Storage::disk('private')->exists($ktpPath)) {
                \Storage::disk('private')->delete($ktpPath);
            }

            if ($e->getCode() == 23000) { // Error code untuk constraint violation
                // Check if the error is specifically about NIM uniqueness
                $errorMessage = $e->getMessage();
                if (
                    strpos(strtolower($errorMessage), 'participants.nim') !== false ||
                    strpos(strtolower($errorMessage), 'nim') !== false ||
                    strpos(strtolower($errorMessage), 'participants_nim_unique') !== false
                ) {

                    // Double-check if this NIM actually exists with confirmed status in non-deleted database records
                    $standardizedNim = strtoupper($request->nim);
                    $existingConfirmedParticipant = Participant::where('nim', $standardizedNim)
                        ->where('status', 'confirmed')
                        ->whereNull('deleted_at') // Only check non-deleted records
                        ->first();

                    // Only show NIM already registered message if it's actually confirmed in non-deleted database
                    if ($existingConfirmedParticipant) {
                        return redirect()->back()
                            ->withErrors(['nim' => 'NIM sudah terdaftar. Jika ingin mengikuti tes ulang, silakan login ke dashboard dan klik tombol "Daftar Tes Ulang".'])
                            ->withInput()
                            ->with('error', 'NIM sudah terdaftar.');
                    } else {
                        // NIM not found in confirmed status in non-deleted records, so this might be a different issue or they can register again
                        return redirect()->back()
                            ->withErrors(['general' => 'Terjadi kesalahan saat menyimpan data. Pastikan semua data yang dimasukkan valid.'])
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba kembali.');
                    }
                } else {
                    // For other constraint violations, provide a more specific error
                    return redirect()->back()
                        ->withErrors(['general' => 'Terjadi kesalahan saat menyimpan data. Pastikan semua data yang dimasukkan valid dan tidak ada duplikasi.'])
                        ->withInput()
                        ->with('error', 'Terjadi kesalahan saat menyimpan data.');
                }
            } else {
                // For other database errors
                return redirect()->back()
                    ->withErrors(['general' => 'Terjadi kesalahan sistem saat menyimpan data. Silakan coba kembali nanti.'])
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan sistem saat menyimpan data.');
            }
        } catch (\Exception $e) {
            // Hapus file yang sudah diupload jika terjadi kesalahan umum
            if ($paymentProofPath && \Storage::disk('private')->exists($paymentProofPath)) {
                \Storage::disk('private')->delete($paymentProofPath);
            }
            if ($photoPath && \Storage::disk('private')->exists($photoPath)) {
                \Storage::disk('private')->delete($photoPath);
            }
            if ($ktpPath && \Storage::disk('private')->exists($ktpPath)) {
                \Storage::disk('private')->delete($ktpPath);
            }

            return redirect()->back()
                ->withErrors(['general' => 'Terjadi kesalahan saat menyimpan data. Silakan coba kembali nanti.'])
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    private function generateSeatNumber($room, $position)
    {
        // Extract room letter (assuming format like A, B, C, etc.)
        preg_match('/^([A-Za-z])/', $room, $matches);
        $roomLetter = isset($matches[1]) ? strtoupper($matches[1]) : 'A';

        // Format position as 2 digits
        $seatNumber = $roomLetter . '-' . str_pad($position, 2, '0', STR_PAD_LEFT);

        return $seatNumber;
    }

    public function showDashboard($id)
    {
        $this->authorizeParticipant($id);
        $participant = Participant::with('schedule')->findOrFail($id);

        // Get all test history for this participant (using NIM to find all related records)
        // Normalize NIM to ensure case-insensitive matching
        $normalizedNim = strtoupper(trim($participant->nim));
        $testHistory = Participant::whereRaw('UPPER(nim) = ?', [$normalizedNim])
            ->with('schedule')
            ->orderBy('id', 'desc')
            ->get();

        return view('participant.dashboard', compact('participant', 'testHistory'));
    }

    public function downloadCard($id)
    {
        $participant = Participant::with('schedule')->findOrFail($id);

        // For now, return a view that can be converted to PDF later
        // In a real implementation, we would use a PDF generator like dompdf
        return view('participant.card', compact('participant'));
    }

    public function applyForRetake($id)
    {
        $this->authorizeParticipant($id);
        $participant = Participant::findOrFail($id);
        $schedules = Schedule::available()->get();

        // Get unique categories from available schedules
        $categories = Schedule::available()
            ->select('category')
            ->distinct()
            ->pluck('category');

        // Get all faculties and study programs for dropdowns
        $faculties = \App\Models\Faculty::all();
        $studyPrograms = \App\Models\StudyProgram::with('faculty')->get();

        return view('participant.retake', compact('participant', 'schedules', 'categories', 'faculties', 'studyPrograms'));
    }

    public function processRetake(Request $request, $id)
    {
        $this->authorizeParticipant($id);
        $participant = Participant::findOrFail($id);

        // Check if the participant has already passed the test before
        if ($participant->passed == 1 || $participant->passed === true) {
            return redirect()->back()->withErrors(['general' => 'Peserta yang telah lulus tidak dapat melakukan pendaftaran ulang.'])->withInput();
        }

        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
            'payment_date' => 'required|date',
            'payment_hour' => 'required|string|max:2',
            'payment_minute' => 'required|string|max:2',
            'payment_second' => 'required|string|max:2',
            'test_category' => 'required|string|max:255',
            'payment_proof' => [
                'required',
                'file',
                'max:1024',
                function ($attribute, $value, $fail) {
                    $fileSizeKB = $value->getSize() / 1024;
                    if ($fileSizeKB > 1024) {
                        $fail('Ukuran file bukti pembayaran melebihi kapasitas maksimal (1MB).');
                        return;
                    }

                    $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                    $mimeType = $value->getMimeType();
                    $extension = strtolower($value->getClientOriginalExtension());

                    if (!in_array($mimeType, $allowedMimes) && !in_array($extension, ['jpg', 'jpeg', 'png'])) {
                        $fail('Kesalahan jenis file yang di-upload. Bukti pembayaran hanya boleh JPG atau PNG.');
                        return;
                    }

                    $imageInfo = @getimagesize($value->getRealPath());
                    if (!$imageInfo) {
                        $fail('Kesalahan jenis file yang di-upload. File bukti pembayaran harus berupa gambar yang valid (JPG atau PNG).');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Combine payment date and time
        $paymentDateTime = $request->payment_date . ' ' . $request->payment_hour . ':' . $request->payment_minute . ':' . $request->payment_second;

        // Detect Duplicate Payment: Check BOTH NIM AND payment timestamp
        $duplicatePayment = Participant::where('nim', $participant->nim)
            ->where('payment_date', $paymentDateTime)
            ->first();

        if ($duplicatePayment) {
            return redirect()->back()
                ->withInput()
                ->with('payment_error', 'Slip pembayaran sudah pernah dipakai. Silakan masukkan data pembayaran yang valid.');
        }

        $schedule = Schedule::findOrFail($request->schedule_id);

        // Check if schedule is full
        if ($schedule->isFull()) {
            return redirect()->back()->withErrors(['schedule_id' => 'Selected schedule is full.'])->withInput();
        }

        // Handle file uploads
        // SECURITY: Store in private storage
        $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'private');

        // Check if payment proof has been used by any other participant
        $existingProof = Participant::where('payment_proof_path', $paymentProofPath)->first();
        if ($existingProof && $existingProof->id != $participant->id) {
            \Storage::disk('private')->delete($paymentProofPath); // Delete the uploaded file
            return redirect()->back()->withErrors(['payment_proof' => 'Payment proof has already been used.'])->withInput();
        }

        try {
            // Create a new participant record for the retake to preserve history
            $newParticipant = $participant->replicate();

            $newParticipant->fill([
                'schedule_id' => $request->schedule_id,
                'temp_seat_number' => $this->generateSeatNumber($schedule->room, 'TBA'),
                'status' => 'pending', // Reset to pending for new payment verification
                'payment_date' => $paymentDateTime,
                'test_category' => $request->test_category,
                'payment_proof_path' => $paymentProofPath,
                'previous_payment_proof_path' => $participant->payment_proof_path, // Explicitly archive OLD proof from PREVIOUS record
                'seat_status' => 'reserved', // Reserved until admin verifies payment
                'seat_number' => 'TBA', // Set to 'TBA' for new registration
                'test_score' => null, // Clear results for new attempt
                'passed' => false,
                'test_date' => null,
                'reading_score' => null,
                'listening_score' => null,
                'speaking_score' => null,
                'writing_score' => null,
                'listening_score_pbt' => null,
                'structure_score_pbt' => null,
                'reading_score_pbt' => null,
                'total_score_pbt' => null,
                'attendance' => null,
                'attendance_marked_at' => null,
                'rejection_message' => null,
                'verification_token' => (string) Str::uuid(), // New token
            ]);

            $newParticipant->save();

            // Update schedule used capacity - increment for NEW registration
            $schedule->increment('used_capacity');

            // Check if new schedule is now full
            if ($schedule->used_capacity >= $schedule->capacity) {
                $schedule->update(['status' => 'full']);
            }

            // Log the participant in by setting the session to the NEW record
            session(['participant_id' => $newParticipant->id]);

            // Log activity
            ActivityLogger::log('Pendaftaran Ulang', 'Peserta ' . $newParticipant->name . ' (NIM: ' . $newParticipant->nim . ') melakukan pendaftaran ulang tes TOEFL.');

            return redirect()->route('participant.dashboard', ['id' => $newParticipant->id])
                ->with([
                    'success' => 'Pendaftaran Ulang Berhasil! Anda telah dialihkan ke dashboard peserta. Bukti pembayaran Anda sedang diverifikasi. Nomor kursi akan ditentukan setelah pembayaran dikonfirmasi oleh admin.',
                    'account_details' => [
                        'username' => $newParticipant->username,
                        'password' => 'Sama dengan akun sebelumnya',
                        'message' => 'Akun Anda tetap sama. Silakan simpan username dan password Anda. Tunggu konfirmasi dari petugas UPA Bahasa Universitas Halu Oleo.'
                    ]
                ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Hapus file yang sudah diupload jika terjadi kesalahan
            \Storage::disk('private')->delete($paymentProofPath);

            \Log::error('Retake registration error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi nanti.')->withInput();
        }
    }

    public function showResubmitPaymentForm($id)
    {
        $this->authorizeParticipant($id);
        $participant = Participant::findOrFail($id);

        // Only allow resubmit payment if the participant's status is rejected
        if ($participant->status !== 'rejected') {
            return redirect()->route('participant.dashboard', $participant->id)
                ->with('error', 'Anda tidak dapat mengunggah ulang bukti pembayaran karena status pendaftaran Anda bukan ditolak.');
        }

        // Return the same registration form view but with indication that it's for resubmission
        $schedules = Schedule::available()->get();

        // Get unique categories from available schedules
        $categories = Schedule::available()
            ->select('category')
            ->distinct()
            ->pluck('category');

        // Get all faculties and study programs for dropdowns
        $faculties = \App\Models\Faculty::all();
        $studyPrograms = \App\Models\StudyProgram::with('faculty')->get();

        return view('participant.resubmit-payment', compact('participant', 'schedules', 'categories', 'faculties', 'studyPrograms'));
    }

    public function processResubmitPayment(Request $request, $id)
    {
        $this->authorizeParticipant($id);
        $participant = Participant::findOrFail($id);

        // Only allow resubmit payment if the participant's status is rejected
        if ($participant->status !== 'rejected') {
            return redirect()->route('participant.dashboard', $participant->id)
                ->with('error', 'Anda tidak dapat mengunggah ulang bukti pembayaran karena status pendaftaran Anda bukan ditolak.');
        }

        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
            'payment_date' => 'required|date',
            'payment_hour' => 'required|string|max:2',
            'payment_minute' => 'required|string|max:2',
            'payment_second' => 'required|string|max:2',
            'payment_proof' => [
                'required',
                'file',
                'max:1024', // 1MB max
                function ($attribute, $value, $fail) {
                    // Check file size first (in KB)
                    $fileSizeKB = $value->getSize() / 1024;
                    if ($fileSizeKB > 1024) {
                        $fail('Ukuran file bukti pembayaran melebihi kapasitas maksimal (1MB).');
                        return;
                    }

                    $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                    $mimeType = $value->getMimeType();
                    $extension = strtolower($value->getClientOriginalExtension());

                    if (!in_array($mimeType, $allowedMimes) && !in_array($extension, ['jpg', 'jpeg', 'png'])) {
                        $fail('Kesalahan jenis file yang di-upload. Bukti pembayaran hanya boleh JPG atau PNG.');
                        return;
                    }

                    // Additional check: verify that the file is actually an image
                    $imageInfo = @getimagesize($value->getRealPath());
                    if (!$imageInfo) {
                        $fail('Kesalahan jenis file yang di-upload. File bukti pembayaran harus berupa gambar yang valid (JPG atau PNG).');
                    }
                },
            ],
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diunggah.',
            'payment_proof.file' => 'Bukti pembayaran harus berupa file.',
            'payment_proof.max' => 'Ukuran file bukti pembayaran maksimal 1MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Combine payment date and time
        $paymentDateTime = $request->payment_date . ' ' . $request->payment_hour . ':' . $request->payment_minute . ':' . $request->payment_second;

        // Detect Duplicate Payment: Check BOTH NIM AND payment timestamp
        $duplicatePayment = Participant::where('nim', $participant->nim)
            ->where('payment_date', $paymentDateTime)
            ->first();

        if ($duplicatePayment) {
            return redirect()->back()
                ->withInput()
                ->with('payment_error', 'Slip pembayaran sudah pernah dipakai. Silakan masukkan data pembayaran yang valid.');
        }

        $schedule = Schedule::findOrFail($request->schedule_id);

        // Check if schedule is full
        if ($schedule->isFull()) {
            return redirect()->back()
                ->withErrors(['schedule_id' => 'Jadwal yang dipilih sudah penuh. Silakan pilih jadwal lain.'])
                ->withInput()
                ->with('error', 'Jadwal yang dipilih sudah penuh.');
        }

        // Handle file upload
        // SECURITY: Store in private storage
        $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'private');

        // Check if payment proof has been used before
        $existingParticipant = Participant::where('payment_proof_path', $paymentProofPath)->first();
        if ($existingParticipant) {
            \Storage::disk('private')->delete($paymentProofPath); // Delete the uploaded file
            return redirect()->back()
                ->withErrors(['payment_proof' => 'Bukti pembayaran sudah pernah digunakan. Silakan unggah bukti pembayaran yang baru.'])
                ->withInput()
                ->with('error', 'Bukti pembayaran sudah pernah digunakan.');
        }

        try {
            // Update participant with new payment details
            $participant->update([
                'schedule_id' => $request->schedule_id,
                'payment_date' => $paymentDateTime,
                'previous_payment_proof_path' => $participant->payment_proof_path, // Archive existing proof
                'payment_proof_path' => $paymentProofPath,
                'status' => 'pending', // Reset status to pending for admin verification
                'rejection_message' => null, // Clear rejection message
                'verification_token' => (string) Str::uuid(), // New attempt, new token
            ]);

            // Increment schedule used capacity if this was the first time updating to pending
            $schedule->increment('used_capacity');

            // Check if schedule is now full
            if ($schedule->used_capacity >= $schedule->capacity) {
                $schedule->update(['status' => 'full']);
            }

            // Log activity
            ActivityLogger::log('Kirim Ulang Bukti Pembayaran', 'Peserta ' . $participant->name . ' (NIM: ' . $participant->nim . ') mengirim ulang bukti pembayaran.');

            return redirect()->route('participant.dashboard', $participant->id)
                ->with([
                    'success' => 'Bukti pembayaran berhasil diunggah ulang. Data pendaftaran Anda sedang menunggu verifikasi dari admin.'
                ]);
        } catch (\Exception $e) {
            // Delete the uploaded file if there's an error
            if ($paymentProofPath && \Storage::disk('private')->exists($paymentProofPath)) {
                \Storage::disk('private')->delete($paymentProofPath);
            }

            return redirect()->back()
                ->withErrors(['general' => 'Terjadi kesalahan saat menyimpan data. Silakan coba kembali nanti.'])
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    private function authorizeParticipant($id)
    {
        $sessionParticipantId = session('participant_id');
        if (!$sessionParticipantId || (string) $sessionParticipantId !== (string) $id) {
            abort(403, 'Unauthorized access.');
        }
    }
    /**
     * SECURITY: Secure file download with authorization
     */
    public function downloadFile($id, $type)
    {
        // Allow access for Super Admin, Admin, and Operator roles
        if (Auth::check() && Auth::user()->isOperator()) {
            $participant = Participant::findOrFail($id);

            // SECURITY: If user is prodi, they can only access files for their own study program
            if (Auth::user()->isProdi() && $participant->study_program_id !== Auth::user()->study_program_id) {
                abort(403, 'Anda tidak memiliki akses ke berkas peserta dari Program Studi lain.');
            }
        } else {
            $this->authorizeParticipant($id);
            $participant = Participant::findOrFail($id);
        }

        $filePath = match ($type) {
            'payment_proof' => $participant->payment_proof_path,
            'previous_payment_proof' => $participant->previous_payment_proof_path,
            'photo' => $participant->photo_path,
            'ktp' => $participant->ktp_path,
            default => abort(404, 'Invalid file type')
        };

        if (!$filePath || !\Storage::disk('private')->exists($filePath)) {
            abort(404, 'File not found');
        } else {
            $fullPath = storage_path('app/private/' . $filePath);
        }

        \Log::info('Secure file access', [
            'participant_id' => $participant->id,
            'file_type' => $type,
            'accessed_by' => Auth::check() ? 'admin' : 'participant',
        ]);

        return response()->file($fullPath, [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Update participant document (payment proof, photo, or KTP)
     */
    public function updateDocument(Request $request, $id)
    {
        $this->authorizeParticipant($id);
        $participant = Participant::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'document_type' => 'required|in:payment_proof,photo,ktp',
            'document_file' => [
                'required',
                'file',
                'max:1024', // 1MB max
                function ($attribute, $value, $fail) use ($request) {
                    // Check file size first (in KB)
                    $fileSizeKB = $value->getSize() / 1024;
                    if ($fileSizeKB > 1024) {
                        $fail('Ukuran file yang di-upload melebihi kapasitas maksimal (1MB).');
                        return;
                    }

                    $docType = $request->document_type;
                    $mimeType = $value->getMimeType();
                    $extension = strtolower($value->getClientOriginalExtension());

                    // All document types only accept JPG and PNG
                    $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                    $allowedExtensions = ['jpg', 'jpeg', 'png'];

                    if (!in_array($mimeType, $allowedMimes) && !in_array($extension, $allowedExtensions)) {
                        $fail('Kesalahan jenis file yang di-upload. File hanya boleh JPG atau PNG.');
                        return;
                    }

                    // Verify image files
                    $imageInfo = @getimagesize($value->getRealPath());
                    if (!$imageInfo) {
                        $fail('Kesalahan jenis file yang di-upload. File harus berupa gambar yang valid (JPG atau PNG).');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal mengupdate dokumen. Silakan periksa file yang diupload.');
        }

        $documentType = $request->document_type;
        $file = $request->file('document_file');

        try {
            // Determine storage directory based on type
            $directory = match ($documentType) {
                'payment_proof' => 'payment_proofs',
                'photo' => 'photos',
                'ktp' => 'ktps',
            };

            // Store new file in private storage
            $newFilePath = $file->store($directory, 'private');

            // Get old file path and backup it before replacing
            $oldFilePath = match ($documentType) {
                'payment_proof' => $participant->payment_proof_path,
                'photo' => $participant->photo_path,
                'ktp' => $participant->ktp_path,
            };

            // Update participant with new file path
            $updateData = match ($documentType) {
                'payment_proof' => [
                    'payment_proof_path' => $newFilePath,
                    'previous_payment_proof_path' => $oldFilePath,
                ],
                'photo' => ['photo_path' => $newFilePath],
                'ktp' => ['ktp_path' => $newFilePath],
            };

            $participant->update($updateData);

            // Delete old file (except we keep previous_payment_proof for record)
            if ($oldFilePath && \Storage::disk('private')->exists($oldFilePath)) {
                if ($documentType !== 'payment_proof') {
                    \Storage::disk('private')->delete($oldFilePath);
                }
            }

            // Log activity
            $docLabel = match ($documentType) {
                'payment_proof' => 'Bukti Pembayaran',
                'photo' => 'Foto Peserta',
                'ktp' => 'Kartu Identitas (KTP)',
            };
            ActivityLogger::log('Update Dokumen', $participant->name . ' (NIM: ' . $participant->nim . ') mengupdate ' . $docLabel . '.');

            return redirect()->route('participant.dashboard', $participant->id)
                ->with('success', $docLabel . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            // Delete uploaded file if error occurs
            if (isset($newFilePath) && \Storage::disk('private')->exists($newFilePath)) {
                \Storage::disk('private')->delete($newFilePath);
            }

            \Log::error('Document update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupdate dokumen. Silakan coba lagi nanti.');
        }
    }
}
