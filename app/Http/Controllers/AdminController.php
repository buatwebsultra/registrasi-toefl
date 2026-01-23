<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Participant;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->isProdi()) {
                // List of allowed methods for Prodi in AdminController
                $allowedMethods = [
                    'profile',
                    'updateProfile',
                    'updatePassword',
                    'adminLogout', // Just in case, though it's usually in AuthController
                ];

                $route = $request->route();
                $action = $route ? $route->getActionMethod() : null;

                if (!in_array($action, $allowedMethods)) {
                    return redirect()->route('prodi.dashboard');
                }
            }
            return $next($request);
        });
    }

    /**
     * Show the admin profile edit form.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    /**
     * Update the admin profile (Name, NIP, Jabatan, Photo).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $updateData = [
            'name' => $request->name,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
        ];

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo_path && \Storage::disk('private')->exists($user->photo_path)) {
                \Storage::disk('private')->delete($user->photo_path);
            }

            // Store new photo in private storage
            $path = $request->file('photo')->store('profile-photos', 'private');
            $updateData['photo_path'] = $path;
        }

        /** @var \App\Models\User $user */
        $user->update($updateData);

        ActivityLogger::log('Update Profil Admin', 'Admin memperbarui profilnya: ' . $user->name);

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update the admin password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => [
                'required',
                'string',
                'min:8', // Adjusted to 8 as per standard, or keep 12 if strict
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ],
        ], [
            'new_password.min' => 'Password minimal harus 8 karakter.',
            'new_password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'current_password.current_password' => 'Password saat ini salah.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        ActivityLogger::log('Ganti Password Admin', 'Admin mengganti passwordnya: ' . $user->name);

        return redirect()->route('admin.profile')->with('success', 'Password berhasil diubah.');
    }

    public function index()
    {
        if (Auth::user()->isProdi()) {
            return redirect()->route('prodi.dashboard');
        }
        $searchDate = request('search_date');
        
        $query = Schedule::withCount('participants')
            ->withCount(['participants as pending_count' => function ($query) {
                $query->where('status', 'pending');
            }]);

        if ($searchDate) {
            $query->whereDate('date', $searchDate);
        }

        // Apply sorting: Date (DESC), Time (DESC)
        $schedules = $query->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Sync status and capacity for the current page
        foreach ($schedules as $schedule) {
            // Sync used_capacity with actual participant count
            if ($schedule->used_capacity !== $schedule->participants_count) {
                $schedule->update(['used_capacity' => $schedule->participants_count]);
            }
            
            // Determine if schedule should be 'full' (either reached capacity, is past, or within 2-day registration window)
            // Logic: Close registration if today is within 2 days of test date
            $isPast = $schedule->date->isPast() && !$schedule->date->isToday();
            
            // Check H-2 logic. If test is on Friday (Day 5), close on Wednesday (Day 3) end of day?
            // User requirement: "sudah 2 hari". usually means D-2.
            $isWithinTwoDays = $schedule->date->lte(now()->addDays(1)->endOfDay());
            
            if (($schedule->used_capacity >= $schedule->capacity || $isPast || $isWithinTwoDays)) {
                if ($schedule->status !== 'full') {
                    $schedule->update(['status' => 'full']);
                }
            } else {
                // If it was full but now conditions are met to be available again (e.g. date changed)
                if ($schedule->status === 'full' && $schedule->used_capacity < $schedule->capacity) {
                    $schedule->update(['status' => 'available']);
                }
            }
        }

        $totalParticipants = Participant::count();
        $availableDates = Schedule::select(DB::raw('DATE(date) as date'))
            ->distinct()
            ->pluck('date')
            ->sort()
            ->values();

        return view('admin.dashboard', compact('schedules', 'totalParticipants', 'availableDates', 'searchDate'));
    }

    public function createSchedule(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'room' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'category' => 'required|string|max:255',
        ]);

        // Check if a schedule already exists with the same date, time and room
        // Use Carbon to consistently format the time for comparison (H:i)
        $inputTimeForComparison = \Carbon\Carbon::parse($request->time)->format('H:i');
        $normalizedRoom = strtolower(trim($request->room));

        // Look for existing schedules with the same date and room (case-insensitive)
        $existingSchedules = Schedule::whereDate('date', $request->date)
            ->whereRaw('LOWER(room) = ?', [$normalizedRoom])
            ->get();

        // Check each existing schedule for time conflict
        foreach ($existingSchedules as $existingSchedule) {
            // Normalize the existing time to the same format for comparison
            $existingTimeForComparison = \Carbon\Carbon::parse($existingSchedule->time)->format('H:i');

            if ($existingTimeForComparison === $inputTimeForComparison) {
                return redirect()->route('admin.dashboard')->with('error', "Ruangan {$request->room} pada tanggal {$request->date} pukul {$request->time} sudah terisi.");
            }
        }

        $schedule = Schedule::create([
            'date' => $request->date,
            'time' => $request->time . ':00', // Ensure consistent format with ':00' seconds
            'room' => $request->room,
            'capacity' => $request->capacity,
            'category' => $request->category,
            'status' => 'available',
            'signature_name' => $request->signature_name,
            'signature_nip' => $request->signature_nip,
        ]);

        ActivityLogger::log('Membuat Jadwal', 'Admin membuat jadwal baru pada tanggal ' . $schedule->date . ' di ruangan ' . $schedule->room);

        return redirect()->route('admin.dashboard')->with('success', 'Schedule created successfully.');
    }

    public function updateSchedule(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'room' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'signature_name' => 'nullable|string|max:255',
            'signature_nip' => 'nullable|string|max:255',
        ]);

        // Check if another schedule already exists with the same date, time and room (excluding current schedule)
        // Use Carbon to consistently format the time for comparison (H:i)
        $inputTimeForComparison = \Carbon\Carbon::parse($request->time)->format('H:i');
        $normalizedRoom = strtolower(trim($request->room));

        // Look for existing schedules with the same date and room (excluding current)
        $existingSchedules = Schedule::whereDate('date', $request->date)
            ->whereRaw('LOWER(room) = ?', [$normalizedRoom])
            ->where('id', '!=', $id) // Exclude current schedule
            ->get();

        // Check each existing schedule for time conflict
        foreach ($existingSchedules as $existingSchedule) {
            // Normalize the existing time to the same format for comparison
            $existingTimeForComparison = \Carbon\Carbon::parse($existingSchedule->time)->format('H:i');

            if ($existingTimeForComparison === $inputTimeForComparison) {
                return redirect()->route('admin.dashboard')->with('error', "Ruangan {$request->room} pada tanggal {$request->date} pukul {$request->time} sudah terisi.");
            }
        }

        $schedule->update([
            'date' => $request->date,
            'time' => $request->time . ':00', // Ensure consistent format with ':00' seconds
            'room' => $request->room,
            'capacity' => $request->capacity,
            'signature_name' => $request->signature_name,
            'signature_nip' => $request->signature_nip,
        ]);

        if ($schedule->used_capacity >= $schedule->capacity) {
            $schedule->update(['status' => 'full']);
        } else {
            // Check if schedule should be closed due to date (H-2 or past)
            $isPast = $schedule->date->isPast() && !$schedule->date->isToday();
            // Registration closes 2 days before test
            $isWithinTwoDays = $schedule->date->lte(now()->addDays(1)->endOfDay());
            
            if ($isPast || $isWithinTwoDays) {
                $schedule->update(['status' => 'full']);
            } else {
                $schedule->update(['status' => 'available']);
            }
        }

        ActivityLogger::log('Memperbarui Jadwal', 'Admin memperbarui jadwal ID: ' . $id);

        return redirect()->route('admin.dashboard')->with('success', 'Schedule updated successfully.');
    }

    public function deleteSchedule($id)
    {
        $schedule = Schedule::findOrFail($id);

        // Check if there are participants registered for this schedule
        if ($schedule->participants()->count() > 0) {
            return redirect()->route('admin.dashboard')->with('error', 'Tidak dapat menghapus jadwal yang memiliki peserta terdaftar.');
        }

        $schedule->delete();

        ActivityLogger::log('Menghapus Jadwal', 'Admin menghapus jadwal ID: ' . $id);

        return redirect()->route('admin.dashboard')->with('success', 'Schedule deleted successfully.');
    }

    public function markScheduleFull($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update(['status' => 'full']);
        
        ActivityLogger::log('Tandai Jadwal Penuh', 'Admin menandai jadwal ID: ' . $id . ' sebagai penuh.');

        return redirect()->route('admin.dashboard')->with('success', 'Schedule marked as full.');
    }

    public function profilePhoto()
    {
        $user = Auth::user();

        if (!$user->photo_path || !\Storage::disk('private')->exists($user->photo_path)) {
            abort(404, 'Profile photo not found');
        }

        $fullPath = storage_path('app/private/' . $user->photo_path);
        
        return response()->file($fullPath);
    }

    public function participantsList($scheduleId)
    {
        if (Auth::user()->isProdi()) {
            return redirect()->route('prodi.dashboard');
        }
        $schedule = Schedule::findOrFail($scheduleId);

        // Ambil parameter pencarian
        $searchNim = request('search_nim');
        $status = request('status');

        $query = $schedule->participants()->with('studyProgram');

        // Jika ada parameter pencarian NIM, cari peserta dengan NIM tertentu
        if ($searchNim) {
            $query->where('nim', $searchNim);
        }
        
        // Filter by status if provided
        if ($status) {
            $query->where('status', $status);
        }

        $participants = $query->paginate(10);

        // Hitung total peserta untuk kebutuhan view
        $totalParticipants = $schedule->participants()->count();

        return view('admin.participants-list', compact('schedule', 'participants', 'searchNim', 'totalParticipants'));
    }

    public function participantDetails($id)
    {
        // SECURITY: Explicit authorization check (defense-in-depth)
        if (!Auth::check() || !Auth::user()->isOperator()) {
            abort(403, 'Unauthorized access');
        }
        
        $participant = Participant::findOrFail($id);

        // SECURITY: If user is prodi, they can only access data for their own study program
        if (Auth::user()->isProdi() && $participant->study_program_id !== Auth::user()->study_program_id) {
            return redirect()->route('prodi.dashboard')->with('error', 'Anda tidak memiliki akses ke data peserta dari Program Studi lain.');
        }

        // Get all test history for this participant (using NIM to find all related records)
        // Normalize NIM to ensure case-insensitive matching
        $normalizedNim = strtoupper(trim($participant->nim));
        $testHistory = Participant::whereRaw('UPPER(nim) = ?', [$normalizedNim])
            ->with('schedule')
            ->orderBy('id', 'desc')
            ->get();

    return view('admin.participant-details', compact('participant', 'testHistory'));
    }

    public function participantPhoto($id)
    {
        // SECURITY: Explicit authorization check
        if (!Auth::check() || !Auth::user()->isOperator()) {
            abort(403, 'Unauthorized access');
        }

        $participant = Participant::findOrFail($id);

        // SECURITY: If user is prodi, they can only access photo for their own study program
        if (Auth::user()->isProdi() && $participant->study_program_id !== Auth::user()->study_program_id) {
            abort(403, 'Anda tidak memiliki akses ke foto peserta dari Program Studi lain.');
        }

        return view('admin.participant-photo', compact('participant'));
    }

    public function resetParticipantPassword(Request $request)
    {
        // SECURITY: Explicit authorization check
        if (!Auth::check() || !Auth::user()->isOperator()) {
            abort(403, 'Unauthorized access');
        }

        $participant = Participant::findOrFail($request->participant_id);

        // SECURITY: If user is prodi, they can only reset password for their own study program
        if (Auth::user()->isProdi() && $participant->study_program_id !== Auth::user()->study_program_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak untuk mereset password peserta dari Program Studi lain.');
        }

        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'new_password' => [
                'required',
                'string',
                'min:12',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ],
        ], [
            'new_password.min' => 'Password minimal harus 12 karakter.',
            'new_password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus (@$!%*?&).',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $participant = Participant::findOrFail($request->participant_id);
        $participant->update([
            'password' => Hash::make($request->new_password),
        ]);

        ActivityLogger::log('Reset Password Peserta', 'Admin/Operator meriset password untuk peserta: ' . $participant->name);

        return redirect()->back()->with('success', 'Participant password reset successfully.');
    }

    public function deleteParticipant($id)
    {
        // SECURITY: Explicit authorization check (defense-in-depth)
        if (!Auth::check() || !Auth::user()->isOperator()) {
            abort(403, 'Unauthorized access');
        }
        
        $participant = Participant::findOrFail($id);

        // Delete associated files from private storage
        if ($participant->photo_path && \Storage::disk('private')->exists($participant->photo_path)) {
            \Storage::disk('private')->delete($participant->photo_path);
        }
        if ($participant->payment_proof_path && \Storage::disk('private')->exists($participant->payment_proof_path)) {
            \Storage::disk('private')->delete($participant->payment_proof_path);
        }
        if ($participant->ktp_path && \Storage::disk('private')->exists($participant->ktp_path)) {
            \Storage::disk('private')->delete($participant->ktp_path);
        }

        $scheduleId = $participant->schedule_id;
        $participantName = $participant->name;
        $participant->delete();

        // Decrement schedule used capacity
        $schedule = Schedule::findOrFail($scheduleId);
        if ($schedule->used_capacity > 0) {
            $schedule->decrement('used_capacity');
        }

        // Update schedule status if it was full
        if ($schedule->capacity > $schedule->used_capacity) {
            $schedule->update(['status' => 'available']);
        }

        ActivityLogger::log('Menghapus Peserta', 'Admin menghapus peserta: ' . $participantName . ' dari jadwal ID: ' . $scheduleId);

        // Redirect to the participants list page for this schedule
        return redirect()->route('admin.participants.list', $scheduleId)->with('success', 'Peserta berhasil dihapus.');
    }

    public function updateTestScore(Request $request, $id)
    {
        // SECURITY: Explicit authorization check (defense-in-depth)
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        $participant = Participant::findOrFail($id);

        // Check if participant is present
        if ($participant->attendance !== 'present') {
            return back()->with('error', 'Nilai hanya dapat diinput untuk peserta yang hadir.');
        }

        // Only handle PBT format validation and processing
        $testFormat = 'PBT'; // Force to PBT since we're removing iBT logic

        // PBT validation - Total score will be the sum of three parts (max 68+68+67=203)
        $request->validate([
            'test_date' => 'required|date',
            'listening_score_pbt' => 'required|numeric|min:0|max:68',
            'structure_score_pbt' => 'required|numeric|min:0|max:68',
            'reading_score_pbt' => 'required|numeric|min:0|max:67',
            'test_format' => 'required|string|in:PBT',
        ]);

        // Update test format to PBT
        $updateData = [
            'test_date' => $request->test_date,
            'test_format' => $testFormat,
        ];

        // PBT scores
        $updateData['listening_score_pbt'] = $request->listening_score_pbt;
        $updateData['structure_score_pbt'] = $request->structure_score_pbt;
        $updateData['reading_score_pbt'] = $request->reading_score_pbt;

        // Calculate and set test score based on formula: ((L+S+R)/3) * 10
        $listening = $request->listening_score_pbt;
        $structure = $request->structure_score_pbt;
        $reading = $request->reading_score_pbt;
        $calculatedTotal = round(($listening + $structure + $reading) * 10 / 3);
        $updateData['test_score'] = $calculatedTotal;

        // Get academic level automatically based on participant's study program
        $studyProgram = \App\Models\StudyProgram::find($participant->study_program_id);
        if ($studyProgram) {
            // Map study program levels to standard academic levels
            // Using the same mapping as in the Participant model for consistency
            $levelMap = [
                'bachelor' => 'undergraduate',
                'sarjana' => 'undergraduate',
                's1' => 'undergraduate',
                'undergraduate' => 'undergraduate',
                'master' => 'master',
                'magister' => 'master',
                's2' => 'master',
                'graduate' => 'master',
                'doctor' => 'doctorate',
                'doktor' => 'doctorate',
                's3' => 'doctorate',
                'doctoral' => 'doctorate',
            ];

            $rawLevel = strtolower($studyProgram->level);
            $academicLevel = $levelMap[$rawLevel] ?? $studyProgram->level;
        } else {
            $academicLevel = $participant->academic_level; // Use existing academic level if study program not found
        }

        $updateData['academic_level'] = $academicLevel;
        $updateData['is_score_validated'] = false; // Reset validation on every score update

    // Use the model's accessor logic to determine the passed status
    // First, fill the participant with the new data
    $participant->fill($updateData);
    
    // The 'passed' attribute is now calculated by the model's accessor getPassedAttribute()
    // We explicitly set it in the update data to ensure it's saved to the database column
    $updateData['passed'] = $participant->passed;

        $participant->update($updateData);

        ActivityLogger::log('Update Nilai', 'Admin memperbarui nilai TOEFL peserta: ' . $participant->name . ' (Total: ' . $participant->test_score . ')');

        return redirect()->route('admin.participant.details', $participant->id)->with('success', 'Nilai test berhasil disimpan dan akan muncul di dashboard peserta.');
    }

    // Update participant attendance status
    public function updateAttendance(Request $request, $id)
    {
        $participant = Participant::findOrFail($id);
        
        // Block if already passed
        if ($participant->passed) {
            return back()->with('error', 'Tidak dapat mengubah kehadiran peserta yang sudah dinyatakan LULUS.');
        }

        // Block if not confirmed (payment validated)
        if ($participant->status !== 'confirmed') {
            return back()->with('error', 'Kehadiran hanya dapat diubah untuk peserta dengan status pembayaran Terkonfirmasi.');
        }

        $request->validate([
            'attendance' => 'required|in:present,absent,permission',
        ]);

        $attendance = $request->attendance;
        $updateData = [
            'attendance' => $attendance,
            'attendance_marked_at' => now(),
        ];

        // If absent, they automatically fail
        if ($attendance === 'absent') {
            $updateData['test_score'] = 0;
            $updateData['passed'] = false;
            // Reset scores if any
            $updateData['listening_score_pbt'] = 0;
            $updateData['structure_score_pbt'] = 0;
            $updateData['reading_score_pbt'] = 0;
            $updateData['test_format'] = 'PBT';
        } 

        $participant->update($updateData);

        ActivityLogger::log('Update Kehadiran', 'Admin/Operator memperbarui kehadiran peserta: ' . $participant->name . ' (' . $attendance . ')');

        return back()->with('success', 'Status kehadiran berhasil diperbarui.');
    }

    public function rescheduleParticipant(Request $request, $id) {
        $participant = Participant::findOrFail($id);
        
        $request->validate([
            'new_schedule_id' => 'required|exists:schedules,id',
        ]);
        
        $newSchedule = Schedule::findOrFail($request->new_schedule_id);
        
        // Check capacity
        if ($newSchedule->used_capacity >= $newSchedule->capacity) {
            return back()->with('error', 'Jadwal yang dipilih sudah penuh.');
        }
        
        // Update participant schedule
        // We keep the old record but soft delete it? Or move it? 
        // Requirement: "admin memindahkan jadwal yang tersedia" -> Implies moving.
        // Changing schedule_id is the simplest way to move.
        
        // Decrement old schedule capacity
        $oldSchedule = $participant->schedule;
        if ($oldSchedule) {
            $oldSchedule->decrement('used_capacity');
            
            // Update old schedule status to available if it was full
            if ($oldSchedule->used_capacity < $oldSchedule->capacity && $oldSchedule->status === 'full') {
                $oldSchedule->update(['status' => 'available']);
            }
        }
        
        // Generate new seat number
        $seatNumber = $this->generateSeatNumber($newSchedule);

        // Update participant
        $participant->update([
            'schedule_id' => $newSchedule->id,
            'seat_number' => $seatNumber, // Assign new seat number
            'seat_status' => 'confirmed', // Auto-confirm seat
            'attendance' => null, // Reset attendance logic for new schedule
            'attendance_marked_at' => null,
            'test_score' => null, // Reset score if they were absent/previous attempt
            'passed' => false,
            // Keep personal data and payment proof
        ]);
        
        // Increment new schedule capacity
        $newSchedule->increment('used_capacity');

        // Check if new schedule is now full
        if ($newSchedule->used_capacity >= $newSchedule->capacity) {
            $newSchedule->update(['status' => 'full']);
        }
        
        return redirect()->route('admin.participants.list', $oldSchedule->id)->with('success', 'Peserta berhasil dipindahkan ke jadwal baru.');
    }

    public function pendingParticipants(Request $request)
    {
        if (Auth::user()->isProdi()) {
            return redirect()->route('prodi.dashboard');
        }

        // Get active schedules for filter
        $schedules = Schedule::where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->get();

        $query = Participant::with(['schedule', 'studyProgram', 'faculty'])
            ->where('status', 'pending');

        // Apply filter if selected
        if ($request->has('schedule_id') && $request->schedule_id != '') {
            $query->where('schedule_id', $request->schedule_id);
        }

        $pendingParticipants = $query->orderBy('created_at', 'desc')->get();

        return view('admin.pending-participants', compact('pendingParticipants', 'schedules'));
    }

    public function confirmParticipant(Request $request, $id)
    {
        $participant = Participant::findOrFail($id);

        // Validate payment date/time if provided
        $validatedData = $request->validate([
            'payment_date' => 'nullable|date',
            'payment_hour' => 'nullable|numeric|min:0|max:23',
            'payment_minute' => 'nullable|numeric|min:0|max:59',
            'payment_second' => 'nullable|numeric|min:0|max:59',
        ]);

        // Update payment_date if provided
        if ($request->filled('payment_date') && $request->filled('payment_hour') && 
            $request->filled('payment_minute') && $request->filled('payment_second')) {
            
            $paymentDateTime = \Carbon\Carbon::parse($request->payment_date)
                ->setTime(
                    $request->payment_hour,
                    $request->payment_minute,
                    $request->payment_second
                );
            
            $participant->payment_date = $paymentDateTime;
        }

        // Only assign seat number if it's still reserved
        if ($participant->seat_status === 'reserved') {
            // Get the schedule to determine the proper seat number
            $schedule = $participant->schedule;

            // Calculate the next available seat number based on current used capacity
            $seatNumber = $this->generateSeatNumber($schedule);

            // Update participant with the assigned seat number and status
            $participant->update([
                'seat_number' => $seatNumber,
                'seat_status' => 'confirmed',
                'status' => 'confirmed',
                'payment_date' => $participant->payment_date
            ]);
        } else {
            // If seat is not reserved, just update the status
            $participant->update([
                'status' => 'confirmed',
                'payment_date' => $participant->payment_date
            ]);
        }

        ActivityLogger::log('Konfirmasi Peserta', 'Admin/Operator mengonfirmasi pendaftaran peserta: ' . $participant->name);

        return redirect()->back()->with('success', 'Peserta berhasil dikonfirmasi dan nomor kursi telah ditetapkan.');
    }

    /**
     * Generate seat number based on room and position
     */
    /**
     * Generate seat number based on schedule room and available slots (filling gaps)
     */
    private function generateSeatNumber($schedule)
    {
        $roomName = $schedule->room; // e.g., UPT-01

        // Get all currently assigned seat numbers for this schedule
        // We only care about confirmed participants who have a seat number assigned
        $existingSeats = $schedule->participants()
            ->whereNotNull('seat_number')
            ->where('seat_number', '!=', '')
            ->where(function($q) {
                $q->where('status', 'confirmed')->orWhere('seat_status', 'confirmed');
            })
            ->pluck('seat_number')
            ->toArray();

        // Extract the numbers from existing seats
        $usedNumbers = [];
        foreach ($existingSeats as $seat) {
            // Expected format: ROOM-XXX (e.g. UPT-01-001) or just numeric part at the end
            // Let's match the last sequence of digits after a hyphen
            if (preg_match('/-(\d+)$/', $seat, $matches)) {
                $usedNumbers[] = (int)$matches[1];
            }
        }

        // Find the first missing number starting from 1
        $seatIndex = 1;
        while (in_array($seatIndex, $usedNumbers)) {
            $seatIndex++;
        }

        // Format: RoomName-XXX (e.g., UPT-01-001)
        return $roomName . '-' . str_pad($seatIndex, 3, '0', STR_PAD_LEFT);
    }

    public function rejectParticipant(Request $request, $id)
    {
        $participant = Participant::findOrFail($id);

        // Update participant status to rejected and add rejection message
        $participant->update([
            'status' => 'rejected',
            'rejection_message' => $request->get('rejection_message', 'Pendaftaran Anda ditolak oleh admin.')
        ]);

        $scheduleId = $participant->schedule_id;

        // Decrement schedule used capacity if the participant had a confirmed seat
        $schedule = Schedule::findOrFail($scheduleId);
        if ($schedule->used_capacity > 0) {
            $schedule->decrement('used_capacity');
        }

        // Update schedule status if it was full
        if ($schedule->capacity > $schedule->used_capacity) {
            $schedule->update(['status' => 'available']);
        }

        return redirect()->back()->with('success', 'Pendaftaran peserta telah ditolak.');
    }

    public function exportParticipants(Request $request)
    {
        // For export, we need to get participants based on the date filter
        // If date is provided, filter by that date; otherwise, get all
        if ($request->has('date') && !empty($request->date)) {
            // If date is specified, get participants for that date
            $participants = Participant::with(['schedule', 'studyProgram', 'faculty'])
                ->whereHas('schedule', function($q) use ($request) {
                    $q->whereDate('date', $request->date);
                })
                ->get();
        } else {
            // If no date is specified, get all participants with their schedules
            $participants = Participant::with(['schedule', 'studyProgram', 'faculty'])->get();
        }

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator("TOEFL Registration System")
            ->setTitle("Daftar Peserta TOEFL")
            ->setSubject("Daftar Peserta TOEFL");

        // Define header row FIRST (before using it)
        $headers = [
            'No', 'Nomor Kursi', 'NIM', 'Nama', 'Jurusan', 'Jenjang', 'Jadwal Tanggal', 'Ruangan', 'Kategori Tes', 'Nilai TOEFL', 'Status Kelulusan'
        ];

        // Add header
        $sheet->setCellValue('A1', 'DAFTAR PESERTA TOEFL');
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $lastHeaderCol = $this->getColumnLetter(count($headers) - 1);
        $sheet->mergeCells('A1:' . $lastHeaderCol . '1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Add date filter info
        if ($request->has('date') && $request->date != '') {
            $sheet->setCellValue('A2', 'Tanggal: ' . \Carbon\Carbon::parse($request->date)->format('d M Y'));
        } else {
            $sheet->setCellValue('A2', 'Semua Tanggal');
        }
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->mergeCells('A2:' . $lastHeaderCol . '2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Add empty row
        $sheet->setCellValue('A3', '');

        // Set headers
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $sheet->getStyle($col . '4')->getFont()->setBold(true);
            $sheet->getStyle($col . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . '4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($col . '4')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THICK);
            $col++;
        }

        // Add data rows
        $row = 5;
        $no = 1;
        foreach ($participants as $participant) {
            $data = [
                $no, // No
                $participant->effective_seat_number,
                $participant->nim,
                $participant->name,
                $participant->major,
                $participant->academic_level_display ?? '',
                $participant->schedule ? $participant->schedule->date->format('d/m/Y') : '',
                $participant->schedule ? $participant->schedule->room : '',
                $participant->test_category,
                $participant->test_score ?? '',
                $participant->passed ? 'Lulus' : ($participant->test_score ? 'Tidak Lulus' : 'Belum Dinilai')
            ];

            $col = 'A';
            foreach ($data as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }

            $no++;
            $row++;
        }

        // Auto-size columns - need to determine the last column based on number of headers
        $headerCount = count($headers);
        for ($i = 0; $i < $headerCount; $i++) {
            $col = $this->getColumnLetter($i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add borders to data cells
        $lastRow = $row - 1;
        if ($lastRow >= 4) { // Only apply borders if there's data
            $lastColumn = $this->getColumnLetter(count($headers) - 1);
            $sheet->getStyle('A4:' . $lastColumn . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        // Set response headers
        $filename = 'peserta_toefl_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);

        ob_clean();
        $writer->save('php://output');
        exit;
    }

    public function exportScheduleParticipants($scheduleId)
    {
        // Get the schedule first to validate it exists
        $schedule = Schedule::findOrFail($scheduleId);

        // Get participants for specific schedule
        $participants = $schedule->participants()->with('studyProgram', 'faculty')->get();

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator("TOEFL Registration System")
            ->setTitle("Daftar Peserta TOEFL Jadwal {$schedule->room} - {$schedule->date->format('d M Y')}")
            ->setSubject("Daftar Peserta TOEFL");

        // Define header row FIRST (before using it)
        // Removed 'Jadwal Tanggal' and 'Ruangan' from columns as they are in the header rows now
        // Added 'Kehadiran'
        $headers = [
            'No', 'Nomor Kursi', 'NIM', 'Nama', 'Jurusan', 'Jenjang', 'Kategori Tes', 'Nilai TOEFL', 'Status Kelulusan', 'Kehadiran'
        ];

        // Row 1: Title
        $sheet->setCellValue('A1', 'DAFTAR PESERTA TOEFL');
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $lastHeaderCol = $this->getColumnLetter(count($headers) - 1);
        $sheet->mergeCells('A1:' . $lastHeaderCol . '1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Row 2: Schedule Info (Combined)
        $sheet->setCellValue('A2', "Jadwal: {$schedule->room} - " . $schedule->date->format('d M Y'));
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->mergeCells('A2:' . $lastHeaderCol . '2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Row 3: Room
        $sheet->setCellValue('A3', "Ruangan : {$schedule->room}");
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->mergeCells('A3:' . $lastHeaderCol . '3');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Row 4: Time
        $sheet->setCellValue('A4', "Jam : " . \Carbon\Carbon::parse($schedule->time)->format('H:i'));
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->mergeCells('A4:' . $lastHeaderCol . '4');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Set columns headers (Row 5)
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '5', $header);
            $sheet->getStyle($col . '5')->getFont()->setBold(true);
            $sheet->getStyle($col . '5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . '5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($col . '5')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THICK);
            $col++;
        }

        // Add data rows
        $row = 6;
        $no = 1;
        foreach ($participants as $participant) {
            // Map attendance status
            $attendanceStatus = '';
            if ($participant->attendance == 'present') {
                $attendanceStatus = 'Hadir';
            } elseif ($participant->attendance == 'absent') {
                $attendanceStatus = 'Tidak Hadir';
            } elseif ($participant->attendance == 'permission') {
                $attendanceStatus = 'Izin';
            }

            $data = [
                $no, // No
                $participant->effective_seat_number,
                $participant->nim,
                $participant->name,
                $participant->major,
                $participant->academic_level_display ?? '',
                // Removed redundant date/room
                $participant->test_category,
                $participant->test_score ?? '',
                $participant->passed ? 'Lulus' : ($participant->test_score ? 'Tidak Lulus' : 'Belum Dinilai'),
                $attendanceStatus // Kehadiran
            ];

            $col = 'A';
            foreach ($data as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }

            $no++;
            $row++;
        }

        // Auto-size columns
        $headerCount = count($headers);
        for ($i = 0; $i < $headerCount; $i++) {
            $col = $this->getColumnLetter($i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add borders to data cells
        $lastRow = $row - 1;
        if ($lastRow >= 5) { // Only apply borders if there's data
            $lastColumn = $this->getColumnLetter(count($headers) - 1);
            $sheet->getStyle('A5:' . $lastColumn . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        // Set response headers
        $filename = 'peserta_toefl_jadwal_' . $schedule->id . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);

        ob_clean();
        $writer->save('php://output');
        exit;
    }

    /**
     * Convert numeric index to Excel column letter (0 -> A, 1 -> B, etc.)
     * @param int $num
     * @return string
     */
    private function getColumnLetter($num) {
        $numeric = $num + 1; // Convert to 1-based index
        $letter = '';

        while ($numeric > 0) {
            $remainder = ($numeric - 1) % 26;
            $letter = chr(65 + $remainder) . $letter;
            $numeric = (int)(($numeric - $remainder - 1) / 26);
        }

        return $letter;
    }

    public function clearScheduleParticipants($scheduleId)
    {
        $schedule = Schedule::findOrFail($scheduleId);

        // Get all participants for this schedule
        $participants = $schedule->participants;
        $count = $participants->count();

        // Delete all participants associated with this schedule
        foreach ($participants as $participant) {
            // Delete related files before deleting the participant
            if ($participant->photo_path && file_exists(storage_path('app/public/' . $participant->photo_path))) {
                \Storage::delete('public/' . $participant->photo_path);
            }
            if ($participant->payment_proof_path && file_exists(storage_path('app/public/' . $participant->payment_proof_path))) {
                \Storage::delete('public/' . $participant->payment_proof_path);
            }
            if ($participant->ktp_path && file_exists(storage_path('app/public/' . $participant->ktp_path))) {
                \Storage::delete('public/' . $participant->ktp_path);
            }

            // Delete the participant record
            $participant->delete();
        }

        // Update schedule used capacity to 0
        $schedule->update([
            'used_capacity' => 0,
            'status' => 'available' // Reset status to available since no participants are registered
        ]);

        ActivityLogger::log('Mengosongkan Jadwal', 'Admin menghapus seluruh peserta (' . $count . ') pada jadwal ID: ' . $scheduleId);

        return redirect()->route('admin.participants.list', ['id' => $schedule->id])
            ->with('success', 'Berhasil menghapus seluruh peserta pada jadwal ' . $schedule->room . ' - ' . $schedule->date->format('d M Y') . '. Jumlah peserta yang dihapus: ' . $count);
    }

    // --- Super Admin Methods ---

    public function manageUsers()
    {
        // Only admin or superadmin can access this
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $users = \App\Models\User::with('studyProgram')
            ->where('role', '!=', \App\Models\User::ROLE_SUPERADMIN)
            ->orderBy('role', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $studyPrograms = \App\Models\StudyProgram::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'studyPrograms'));
    }

    public function storeUser(Request $request)
    {
        // Only admin or superadmin can access this
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,operator,prodi',
            'study_program_id' => 'required_if:role,prodi|exists:study_programs,id|nullable',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
        ], [
            'role.required' => 'Peran user wajib dipilih.',
            'study_program_id.required_if' => 'Program Studi wajib dipilih jika peran adalah Admin Program Studi.',
            'study_program_id.exists' => 'Program Studi yang dipilih tidak valid.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'study_program_id' => $request->role === 'prodi' ? $request->study_program_id : null,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
        ]);

        ActivityLogger::log('Menambah User', 'Super Admin menambah user baru: ' . $user->name . ' (' . $user->role . ')');

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function updateUser(Request $request, $id)
    {
        // Only admin or superadmin can access this
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $user = \App\Models\User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,operator,prodi',
            'study_program_id' => 'required_if:role,prodi|exists:study_programs,id|nullable',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
        ], [
            'role.required' => 'Peran user wajib dipilih.',
            'study_program_id.required_if' => 'Program Studi wajib dipilih jika peran adalah Admin Program Studi.',
            'study_program_id.exists' => 'Program Studi yang dipilih tidak valid.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'study_program_id' => $request->role === 'prodi' ? $request->study_program_id : null,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        ActivityLogger::log('Memperbarui User', 'Super Admin memperbarui data user: ' . $user->name);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $user = \App\Models\User::findOrFail($id);
        
        if ($user->role === \App\Models\User::ROLE_SUPERADMIN) {
            return redirect()->back()->with('error', 'Cannot delete superadmin.');
        }

        $userName = $user->name;
        $user->delete();

        ActivityLogger::log('Menghapus User', 'Super Admin menghapus user: ' . $userName);

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function activityLogs()
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $logs = \App\Models\ActivityLog::orderBy('created_at', 'desc')->paginate(50);

        return view('admin.logs.index', compact('logs'));
    }

    public function downloadLogs(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Validate date inputs
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Build query with date filters
        $query = \App\Models\ActivityLog::orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $logs = $query->get();

        // Generate filename based on date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $filename = 'activity_logs_' . $request->start_date . '_to_' . $request->end_date . '.txt';
        } elseif ($request->filled('start_date')) {
            $filename = 'activity_logs_from_' . $request->start_date . '.txt';
        } elseif ($request->filled('end_date')) {
            $filename = 'activity_logs_until_' . $request->end_date . '.txt';
        } else {
            $filename = 'activity_logs_all_' . date('Y-m-d') . '.txt';
        }

        // Generate text content
        $content = $this->generateLogTextContent($logs, $request);

        // Log this download action
        ActivityLogger::log('Download Log Aktivitas', 'SuperAdmin mendownload log aktivitas' . 
            ($request->filled('start_date') ? ' dari ' . $request->start_date : '') .
            ($request->filled('end_date') ? ' sampai ' . $request->end_date : ''));

        // Return as downloadable file
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function generateLogTextContent($logs, $request)
    {
        $content = "=================================================================\n";
        $content .= "          RIWAYAT AKTIVITAS SIPENA UHO            \n";
        $content .= "=================================================================\n\n";

        // Add filter information
        if ($request->filled('start_date') || $request->filled('end_date')) {
            $content .= "FILTER TANGGAL:\n";
            if ($request->filled('start_date')) {
                $content .= "Dari    : " . \Carbon\Carbon::parse($request->start_date)->format('d F Y') . "\n";
            }
            if ($request->filled('end_date')) {
                $content .= "Sampai  : " . \Carbon\Carbon::parse($request->end_date)->format('d F Y') . "\n";
            }
        } else {
            $content .= "SEMUA RIWAYAT AKTIVITAS\n";
        }

        $content .= "Diunduh pada: " . now()->format('d F Y, H:i:s') . "\n";
        $content .= "Total Log: " . $logs->count() . " aktivitas\n";
        $content .= "\n=================================================================\n\n";

        if ($logs->isEmpty()) {
            $content .= "Tidak ada aktivitas tercatat untuk periode yang dipilih.\n";
        } else {
            foreach ($logs as $index => $log) {
                $content .= sprintf("[%d] %s\n", $index + 1, str_repeat("-", 60));
                $content .= sprintf("Waktu       : %s\n", $log->created_at->format('d/m/Y H:i:s'));
                $content .= sprintf("User        : %s (%s)\n", $log->user_name, ucfirst($log->user_type));
                $content .= sprintf("Kegiatan    : %s\n", $log->action);
                $content .= sprintf("Keterangan  : %s\n", $log->description ?: '-');
                $content .= sprintf("IP Address  : %s\n", $log->ip_address ?: '-');
                $content .= "\n";
            }
        }

        $content .= "=================================================================\n";
        $content .= "                         END OF LOGS                            \n";
        $content .= "=================================================================\n";

        return $content;
    }

    public function validateScore($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $participant = Participant::findOrFail($id);
        
        if ($participant->test_score === null) {
            return back()->with('error', 'Peserta belum memiliki nilai untuk divalidasi.');
        }

        $participant->update([
            'is_score_validated' => true,
            'score_validated_at' => now(),
        ]);

        ActivityLogger::log('Validasi Nilai', 'Admin memvalidasi nilai untuk peserta: ' . $participant->name);

        return back()->with('success', 'Nilai berhasil divalidasi dan sekarang terlihat oleh peserta.');
    }

    public function bulkValidateScores(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $participantIds = $request->input('participant_ids', []);
        
        if (empty($participantIds)) {
            return back()->with('error', 'Pilih setidaknya satu peserta untuk divalidasi.');
        }

        $participants = Participant::whereIn('id', $participantIds)
            ->whereNotNull('test_score')
            ->get();

        foreach ($participants as $participant) {
            $participant->update([
                'is_score_validated' => true,
                'score_validated_at' => now(),
            ]);
        }

        ActivityLogger::log('Validasi Masal Nilai', 'Admin memvalidasi nilai untuk ' . $participants->count() . ' peserta secara masal.');

        return back()->with('success', $participants->count() . ' nilai peserta berhasil divalidasi.');
    }

    public function fixSeatNumbers()
    {
        // Only superadmin or admin can access this
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $schedules = \App\Models\Schedule::all();
        $totalFixed = 0;

        foreach ($schedules as $schedule) {
            // Get all confirmed participants for this schedule, ordered by created_at (registration time)
            $participants = $schedule->participants()
                ->where(function($q) {
                    $q->where('status', 'confirmed')->orWhere('seat_status', 'confirmed');
                })
                ->orderBy('created_at', 'asc')
                ->get();

            $seatIndex = 1;
            foreach ($participants as $participant) {
                // Generate new seat number: RoomName-XXX
                $newSeatNumber = $schedule->room . '-' . str_pad($seatIndex, 3, '0', STR_PAD_LEFT);
                
                // Update if different
                if ($participant->seat_number !== $newSeatNumber) {
                    $participant->update(['seat_number' => $newSeatNumber]);
                    $totalFixed++;
                }
                
                $seatIndex++;
            }
            
            // Update used_capacity to match actual count of confirmed participants
            $confirmedCount = $participants->count();
            if ($schedule->used_capacity !== $confirmedCount) {
                 $schedule->update(['used_capacity' => $confirmedCount]);
            }
        }

        return redirect()->route('admin.dashboard')->with('success', "Berhasil memperbaiki {$totalFixed} nomor kursi peserta.");
    }

    public function exportAttendanceList($scheduleId)
    {
        $schedule = Schedule::findOrFail($scheduleId);

        // Fetch ONLY confirmed participants
        $participants = $schedule->participants()
            // We only want confirmed participants for the attendance list
            ->where(function($q) {
                $q->where('status', 'confirmed')
                  ->orWhere('seat_status', 'confirmed');
            })
            ->with(['studyProgram', 'faculty'])
            ->orderBy('seat_number', 'asc') // Order by seat number
            // Fallback sort by name if seat number is missing/duplicates
            ->orderBy('name', 'asc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // --- Header Section ---
        // Title
        $sheet->setCellValue('A1', 'DAFTAR HADIR PESERTA TOEFL');
        
        $sheet->mergeCells('A1:G1'); 
        
        $styleTitle = [
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1')->applyFromArray($styleTitle);

        // Schedule Info
        // Using Carbon for Indonesian date format requires proper locale setting or manual array
        // Let's use manual mapping or simple format to ensure compatibility without extra resource loading
        \Carbon\Carbon::setLocale('id'); // Attempt to set locale
        
        $sheet->setCellValue('A3', 'HARI / TANGGAL');
        $sheet->setCellValue('C3', ': ' . strtoupper($schedule->date->translatedFormat('l, d F Y'))); // localized date
        
        $sheet->setCellValue('A4', 'PUKUL');
        $sheet->setCellValue('C4', ': ' . \Carbon\Carbon::parse($schedule->time)->format('H:i') . ' WITA');
        
        $sheet->setCellValue('A5', 'TEMPAT');
        $sheet->setCellValue('C5', ': ' . $schedule->room);

        // --- Table Header ---
        // Columns matching typical attendance list
        $headers = ['NO', 'NO PESERTA', 'NAMA PESERTA', 'NIM', 'GENDER', 'JURUSAN', 'TANDA TANGAN'];
        $headerRow = 7;
        
        $colIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($colIndex . $headerRow, $header);
            $colIndex++;
        }
        
        // Manual column width adjustments
        $sheet->getColumnDimension('A')->setWidth(5); // No
        $sheet->getColumnDimension('B')->setWidth(18); // No Peserta
        $sheet->getColumnDimension('C')->setWidth(35); // Nama
        $sheet->getColumnDimension('D')->setWidth(15); // NIM
        $sheet->getColumnDimension('E')->setWidth(10); // Gender
        $sheet->getColumnDimension('F')->setWidth(25); // Jurusan
        $sheet->getColumnDimension('G')->setWidth(20); // Tanda Tangan

        $styleHeader = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE0E0E0'],
            ],
        ];
        $sheet->getStyle('A7:G7')->applyFromArray($styleHeader);
        $sheet->getRowDimension($headerRow)->setRowHeight(25);

        // --- Data Rows ---
        $row = 8;
        $no = 1;
        
        foreach ($participants as $participant) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $participant->seat_number ?: '-'); 
            $sheet->setCellValue('C' . $row, strtoupper($participant->name));
            $sheet->setCellValue('D' . $row, $participant->nim);
            
            // Gender Column
            $gender = $participant->gender == 'male' ? 'L' : 'P';
            $sheet->setCellValue('E' . $row, $gender);
            
            // Study Program access
            $studyProgram = $participant->relationLoaded('studyProgram') ? $participant->getRelation('studyProgram') : $participant->studyProgram;
            $studyProgramName = (is_object($studyProgram) && isset($studyProgram->name)) ? $studyProgram->name : '-';
            
            $sheet->setCellValue('F' . $row, $studyProgramName);
            
            // Signature cell remains empty for manual signature
            
            // Set row height for signature space
            $sheet->getRowDimension($row)->setRowHeight(35);
            
            $no++;
            $row++;
        }
        
        // Apply borders to all data rows
        $lastRow = $row - 1;
        $styleTable = [
             'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ];
        
        if ($lastRow >= 8) {
             $sheet->getStyle('A8:G' . $lastRow)->applyFromArray($styleTable);
             // Center align No, No Peserta, NIM
             $sheet->getStyle('A8:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
             $sheet->getStyle('D8:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
             $sheet->getStyle('E8:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Center Gender
             $sheet->getStyle('C8:C' . $lastRow)->getAlignment()->setIndent(1); // Small indent for name
        }

        // --- Summary Section ---
        $summaryRow = $lastRow + 2;
        
        $sheet->setCellValue('B' . $summaryRow, 'Jumlah Peserta Hadir');
        $sheet->setCellValue('C' . $summaryRow, ': ....................... Orang');
        
        $sheet->setCellValue('B' . ($summaryRow + 1), 'Jumlah Peserta Tidak Hadir');
        $sheet->setCellValue('C' . ($summaryRow + 1), ': ....................... Orang');

        // Signature Section
        $signatureRow = $summaryRow + 3;
        $sheet->setCellValue('F' . $signatureRow, 'Pengawas / Petugas,');
        $sheet->getStyle('F' . $signatureRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('F' . ($signatureRow + 4), '( ....................................................... )');
        $sheet->getStyle('F' . ($signatureRow + 4))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Filename: DAFTAR_HADIR_YYYY-MM-DD_ROOM.xlsx
        $fileName = 'DAFTAR_HADIR_' . $schedule->date->format('Y-m-d') . '_' . \Str::slug($schedule->room) . '.xlsx';
        
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName) .'"');
        $writer->save('php://output');
        exit;
    }
}
