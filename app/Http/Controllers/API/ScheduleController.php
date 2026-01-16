<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $schedules = Schedule::withCount('participants')->get();
        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    /**
     * Display available schedules only.
     */
    public function available(): JsonResponse
    {
        $schedules = Schedule::where('status', 'available')
            ->whereRaw('used_capacity < capacity')
            ->withCount('participants')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'date' => 'required|date',
                'room' => 'required|string|max:255',
                'capacity' => 'required|integer|min:1',
                'category' => 'required|string|max:255',
            ]);

            $schedule = Schedule::create([
                'date' => $request->date,
                'room' => $request->room,
                'capacity' => $request->capacity,
                'category' => $request->category,
                'status' => 'available'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule created successfully.',
                'data' => $schedule
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
                'message' => 'An error occurred while creating the schedule.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $schedule = Schedule::withCount('participants')->find($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $schedule = Schedule::findOrFail($id);

            $request->validate([
                'date' => 'required|date',
                'room' => 'required|string|max:255',
                'capacity' => 'required|integer|min:1',
                'category' => 'required|string|max:255',
            ]);

            $schedule->update([
                'date' => $request->date,
                'room' => $request->room,
                'capacity' => $request->capacity,
                'category' => $request->category,
            ]);

            // Update status based on capacity
            if ($schedule->used_capacity >= $schedule->capacity) {
                $schedule->update(['status' => 'full']);
            } else {
                $schedule->update(['status' => 'available']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully.',
                'data' => $schedule
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
                'message' => 'An error occurred while updating the schedule.',
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
            $schedule = Schedule::findOrFail($id);

            // Check if there are participants registered for this schedule
            if ($schedule->participants()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete schedule with registered participants.'
                ], 400);
            }

            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Schedule deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the schedule.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}