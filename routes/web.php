<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\AuthController;
use App\Models\Schedule;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home route
Route::get('/', function () {
    $latestSchedules = Schedule::where('status', 'available')
                               ->whereColumn('used_capacity', '<', 'capacity')
                               ->orderBy('date', 'asc')
                               ->orderBy('time', 'asc')
                               ->get();
    
    $galleryItems = \App\Models\GalleryItem::where('is_active', true)->latest()->get();

    return view('welcome', compact('latestSchedules', 'galleryItems'));
})->name('home');

// Participant routes
Route::middleware(['participant'])->prefix('participant')->group(function () {
    // Registration routes are NOW PUBLIC (moved below)
    
    Route::get('/dashboard/{id}', [ParticipantController::class, 'showDashboard'])->name('participant.dashboard');
    
    Route::get('/card/preview/{id}', [PDFController::class, 'showTestCardPreview'])->name('participant.card.preview');
    Route::get('/card/download/{id}', [PDFController::class, 'generateTestCard'])->name('participant.card.download');
    Route::get('/certificate/download/{id}', [PDFController::class, 'generateCertificate'])->name('participant.certificate.download');
    Route::get('/retake/{id}', [ParticipantController::class, 'applyForRetake'])->name('participant.retake.form');
    Route::post('/retake/{id}', [ParticipantController::class, 'processRetake'])->name('participant.retake.process')->middleware('throttle:5,1'); // SECURITY: Rate limit 5/min
    Route::get('/resubmit-payment/{id}', [ParticipantController::class, 'showResubmitPaymentForm'])->name('participant.resubmit.payment.form');
    Route::post('/resubmit-payment/{id}', [ParticipantController::class, 'processResubmitPayment'])->name('participant.resubmit.payment')->middleware('throttle:3,1'); // SECURITY: Rate limit 3/min
    Route::post('/document/update/{id}', [ParticipantController::class, 'updateDocument'])->name('participant.document.update')->middleware('throttle:5,1'); // SECURITY: Rate limit 5/min
});


// PUBLIC Participant Routes (Registration & Login)
Route::prefix('participant')->group(function () {
    Route::get('/register', [ParticipantController::class, 'showRegistrationForm'])->name('participant.register.form');
    Route::post('/register', [ParticipantController::class, 'register'])->name('participant.register')->middleware('throttle:10,1');
});

// SECURITY: Secure file download with authorization - accessible by both participant and admin
Route::get('/participant/file/{id}/{type}', [ParticipantController::class, 'downloadFile'])->name('participant.file.download');


// Public route for QR code verification (accessible without authentication)
Route::get('/participant/card/{id}/{token}', [PDFController::class, 'showTestCard'])->name('participant.card.show');
Route::get('/participant/certificate/verify/{id}/{token}', [PDFController::class, 'showCertificate'])->name('participant.certificate.show');

// Admin routes
Route::middleware(['operator'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/schedule/create', [AdminController::class, 'createSchedule'])->name('admin.schedule.create')->middleware('admin');
    Route::put('/schedule/{id}/update', [AdminController::class, 'updateSchedule'])->name('admin.schedule.update')->middleware('admin');
    Route::delete('/schedule/{id}/delete', [AdminController::class, 'deleteSchedule'])->name('admin.schedule.delete')->middleware('admin');
    Route::post('/schedule/{id}/mark-full', [AdminController::class, 'markScheduleFull'])->name('admin.schedule.mark-full')->middleware('admin');
    Route::get('/fix-seat-numbers', [AdminController::class, 'fixSeatNumbers'])->name('admin.fix.seats');
    Route::get('/schedule/{id}/participants', [AdminController::class, 'participantsList'])->name('admin.participants.list');
    Route::get('/participant/{id}/details', [AdminController::class, 'participantDetails'])->name('admin.participant.details');
    Route::get('/participant/{id}/photo', [AdminController::class, 'participantPhoto'])->name('admin.participant.photo');
    Route::delete('/participant/{id}/delete', [AdminController::class, 'deleteParticipant'])->name('admin.participant.delete');

    // Faculty and Study Program routes
    Route::prefix('faculties')->group(function () {
        Route::get('/', [FacultyController::class, 'index'])->name('admin.faculties.index');
        Route::get('/create', [FacultyController::class, 'createFaculty'])->name('admin.faculties.create');
        Route::post('/store', [FacultyController::class, 'storeFaculty'])->name('admin.faculties.store');
        Route::get('/{id}/edit', [FacultyController::class, 'editFaculty'])->name('admin.faculties.edit');
        Route::put('/{id}/update', [FacultyController::class, 'updateFaculty'])->name('admin.faculties.update');
        Route::delete('/{id}/delete', [FacultyController::class, 'deleteFaculty'])->name('admin.faculties.delete');
    });

    Route::prefix('study-programs')->group(function () {
        Route::get('/', [FacultyController::class, 'studyProgramsIndex'])->name('admin.study-programs.index');
        Route::get('/create', [FacultyController::class, 'createStudyProgram'])->name('admin.study-programs.create');
        Route::post('/store', [FacultyController::class, 'storeStudyProgram'])->name('admin.study-programs.store');
        Route::get('/{id}/edit', [FacultyController::class, 'editStudyProgram'])->name('admin.study-programs.edit');
        Route::put('/{id}/update', [FacultyController::class, 'updateStudyProgram'])->name('admin.study-programs.update');
        Route::delete('/{id}/delete', [FacultyController::class, 'deleteStudyProgram'])->name('admin.study-programs.delete');
    });

    // Test score routes
    Route::put('/participant/{id}/score', [AdminController::class, 'updateTestScore'])->name('admin.participant.score.update')->middleware('admin');
    Route::post('/participant/{id}/score/validate', [AdminController::class, 'validateScore'])->name('admin.participant.score.validate')->middleware('admin');
    Route::post('/participants/score/bulk-validate', [AdminController::class, 'bulkValidateScores'])->name('admin.participants.score.bulk-validate')->middleware('admin');

    // Export participants route
    Route::get('/participants/export', [AdminController::class, 'exportParticipants'])->name('admin.participants.export');

    // Export participants for specific schedule
    Route::get('/schedule/{schedule}/participants/export', [AdminController::class, 'exportScheduleParticipants'])->name('admin.schedule.participants.export');

    // Clear all participants for specific schedule
    Route::post('/schedule/{schedule}/participants/clear', [AdminController::class, 'clearScheduleParticipants'])->name('admin.schedule.clear-participants');

        // Export attendance list (Daftar Hadir)
        Route::get('/schedule/{schedule}/attendance/export', [AdminController::class, 'exportAttendanceList'])->name('admin.schedule.attendance.export');

    // Card generation routes for admin
    Route::get('/participant/{id}/card/preview', [PDFController::class, 'showTestCardPreview'])->name('admin.participant.card.preview')->middleware('admin');
    Route::get('/participant/{id}/card/download', [PDFController::class, 'generateTestCard'])->name('admin.participant.card.download')->middleware('admin');
    Route::get('/participant/{id}/certificate/download', [PDFController::class, 'generateCertificate'])->name('admin.participant.certificate.download');

    // Pending validation routes
    Route::get('/participants/pending', [AdminController::class, 'pendingParticipants'])->name('admin.participants.pending');

    // Gallery Routes
    Route::resource('gallery', \App\Http\Controllers\Admin\GalleryController::class)->names([
        'index' => 'admin.gallery.index',
        'store' => 'admin.gallery.store',
        'update' => 'admin.gallery.update',
        'destroy' => 'admin.gallery.destroy',
    ])->except(['create', 'edit', 'show']);
    Route::put('/participant/{id}/confirm', [AdminController::class, 'confirmParticipant'])->name('admin.participant.confirm');
    Route::put('/participant/{id}/reject', [AdminController::class, 'rejectParticipant'])->name('admin.participant.reject');

    // Attendance and Reschedule routes
    Route::put('/participant/{id}/attendance', [AdminController::class, 'updateAttendance'])->name('admin.participant.attendance.update');
    Route::put('/participant/{id}/reschedule', [AdminController::class, 'rescheduleParticipant'])->name('admin.participant.reschedule');

    // Super Admin routes
    Route::middleware('admin')->group(function () { // SUPERADMIN is checked inside specific methods if needed, or we use 'admin' as a proxy for high-level
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('admin.users.index');
        Route::post('/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::put('/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        
        Route::get('/logs', [AdminController::class, 'activityLogs'])->name('admin.logs.index');
        Route::post('/logs/download', [AdminController::class, 'downloadLogs'])->name('admin.logs.download');
    });

    // Admin Profile Routes
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/profile/photo', [AdminController::class, 'profilePhoto'])->name('admin.profile.photo');
    Route::put('/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::put('/profile/password', [AdminController::class, 'updatePassword'])->name('admin.profile.password.update');

    // Auth-wrapped routes (now inside operator group for extra security)
    Route::group(['middleware' => ['auth']], function () {
        Route::put('/change-password', [AuthController::class, 'changePassword'])->name('admin.change.password');
        Route::put('/participant/reset-password', [AdminController::class, 'resetParticipantPassword'])->name('admin.reset.participant.password');
    });

    // Prodi Dashboard Route
    Route::get('/prodi/dashboard', [\App\Http\Controllers\ProdiController::class, 'index'])->name('prodi.dashboard');
});

// Authentication routes
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login.form');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login')->middleware('throttle:5,1'); // 5 attempts per minute
Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

Route::get('/participant/login', [AuthController::class, 'showParticipantLoginForm'])->name('participant.login');
Route::post('/participant/login', [AuthController::class, 'participantLogin'])->name('participant.login.post')->middleware('throttle:5,1'); // 5 attempts per minute
Route::post('/participant/logout', [AuthController::class, 'participantLogout'])->name('participant.logout');

// Final check: Remove redundant auth group if empty or move to more specific locations
// Already moved to operator group above for consistency.

