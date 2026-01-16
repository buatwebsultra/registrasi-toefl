<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $participants = Participant::with('schedule')->get();
        return response()->json([
            'success' => true,
            'data' => $participants
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'schedule_id' => 'required|exists:schedules,id',
                'nim' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'gender' => 'required|in:male,female',
                'birth_place' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'email' => 'required|email|max:255',
                'major' => 'required|string|max:255',
                'faculty' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'payment_date' => 'required|date',
                'test_category' => 'required|string|max:255',
                'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'photo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $schedule = Schedule::findOrFail($request->schedule_id);

            // Check if schedule is full
            if ($schedule->isFull()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected schedule is full.'
                ], 400);
            }

            // Check if payment proof has been used before
            if ($request->hasFile('payment_proof')) {
                $payment_proof_path = $request->file('payment_proof')->store('payment_proofs', 'public');

                $existingParticipant = Participant::where('payment_proof_path', $payment_proof_path)->first();
                if ($existingParticipant) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment proof has already been used.'
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment proof is required.'
                ], 400);
            }

            // Handle file uploads
            $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            $photoPath = $request->file('photo')->store('photos', 'public');
            $ktpPath = $request->file('ktp')->store('ktps', 'public');

            // Generate seat number
            $seatNumber = $this->generateSeatNumber($schedule->room, $schedule->used_capacity + 1);

            // Create participant
            $participant = Participant::create([
                'schedule_id' => $request->schedule_id,
                'seat_number' => $seatNumber,
                'status' => 'confirmed', // After validation, set to confirmed
                'nim' => $request->nim,
                'name' => $request->name,
                'gender' => $request->gender,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'email' => $request->email,
                'major' => $request->major,
                'faculty' => $request->faculty,
                'phone' => $request->phone,
                'payment_date' => $request->payment_date,
                'test_category' => $request->test_category,
                'payment_proof_path' => $paymentProofPath,
                'photo_path' => $photoPath,
                'ktp_path' => $ktpPath,
            ]);

            // Update schedule used capacity
            $schedule->increment('used_capacity');

            // Check if schedule is now full
            if ($schedule->used_capacity >= $schedule->capacity) {
                $schedule->update(['status' => 'full']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Your seat number is ' . $seatNumber,
                'data' => $participant
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while registering participant.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $participant = Participant::with('schedule')->find($id);

        if (!$participant) {
            return response()->json([
                'success' => false,
                'message' => 'Participant not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $participant
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $participant = Participant::findOrFail($id);

            $request->validate([
                'schedule_id' => 'sometimes|exists:schedules,id',
                'seat_number' => 'sometimes|string|max:255',
                'status' => 'sometimes|in:pending,confirmed,cancelled',
                'nim' => 'sometimes|string|max:255',
                'name' => 'sometimes|string|max:255',
                'gender' => 'sometimes|in:male,female',
                'birth_place' => 'sometimes|string|max:255',
                'birth_date' => 'sometimes|date',
                'email' => 'sometimes|email|max:255',
                'major' => 'sometimes|string|max:255',
                'faculty' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|max:255',
                'payment_date' => 'sometimes|date',
                'test_category' => 'sometimes|string|max:255',
            ]);

            $oldScheduleId = $participant->schedule_id;
            $participant->update($request->all());

            // If schedule changed, update capacity for both old and new schedules
            if ($request->has('schedule_id') && $request->schedule_id != $oldScheduleId) {
                // Decrement old schedule capacity
                $oldSchedule = Schedule::find($oldScheduleId);
                if ($oldSchedule) {
                    $oldSchedule->decrement('used_capacity');
                    if ($oldSchedule->used_capacity < $oldSchedule->capacity) {
                        $oldSchedule->update(['status' => 'available']);
                    }
                }

                // Increment new schedule capacity
                $newSchedule = Schedule::find($request->schedule_id);
                if ($newSchedule) {
                    $newSchedule->increment('used_capacity');
                    if ($newSchedule->used_capacity >= $newSchedule->capacity) {
                        $newSchedule->update(['status' => 'full']);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Participant updated successfully.',
                'data' => $participant
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the participant.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $participant = Participant::findOrFail($id);
            $schedule = $participant->schedule;

            $participant->delete();

            // Update schedule capacity
            if ($schedule) {
                $schedule->decrement('used_capacity');
                if ($schedule->used_capacity < $schedule->capacity) {
                    $schedule->update(['status' => 'available']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Participant deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the participant.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display participants for a specific schedule.
     */
    public function bySchedule($scheduleId): JsonResponse
    {
        $schedule = Schedule::find($scheduleId);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found.'
            ], 404);
        }

        $participants = Participant::where('schedule_id', $scheduleId)
            ->with('schedule')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $participants
        ]);
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
}