<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\ParticipantController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// SECURITY: All API routes now require admin authentication
Route::middleware(['auth', 'admin'])->group(function () {
    
    // Schedule related API endpoints
    Route::prefix('schedules')->group(function () {
        Route::get('/', [ScheduleController::class, 'index']);
        Route::get('/available', [ScheduleController::class, 'available']);
        Route::get('/{id}', [ScheduleController::class, 'show']);
        Route::post('/', [ScheduleController::class, 'store']);
        Route::put('/{id}', [ScheduleController::class, 'update']);
        Route::delete('/{id}', [ScheduleController::class, 'destroy']);
    });

    // Participant related API endpoints
    Route::prefix('participants')->group(function () {
        Route::get('/', [ParticipantController::class, 'index']);
        Route::get('/{id}', [ParticipantController::class, 'show']);
        Route::post('/', [ParticipantController::class, 'store']);
        Route::put('/{id}', [ParticipantController::class, 'update']);
        Route::delete('/{id}', [ParticipantController::class, 'destroy']);
        Route::get('/schedule/{scheduleId}', [ParticipantController::class, 'bySchedule']);
    });

    // Additional endpoints
    Route::get('/schedules/{id}/participants', [ParticipantController::class, 'bySchedule']);
    
});