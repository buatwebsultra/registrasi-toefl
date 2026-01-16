<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ScheduleDateValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that date validation works correctly for past dates
     */
    public function test_date_validation_rejects_past_dates(): void
    {
        $validator = Validator::make([
            'date' => Carbon::yesterday()->format('Y-m-d'),
            'room' => 'A101',
            'capacity' => 10,
            'category' => 'TOEFL ITP'
        ], [
            'date' => 'required|date|after_or_equal:today',
            'room' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'category' => 'required|string|max:255',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('date', $validator->errors()->toArray());
    }

    /**
     * Test that date validation accepts today's date
     */
    public function test_date_validation_accepts_today_date(): void
    {
        $validator = Validator::make([
            'date' => Carbon::today()->format('Y-m-d'),
            'room' => 'A101',
            'capacity' => 10,
            'category' => 'TOEFL ITP'
        ], [
            'date' => 'required|date|after_or_equal:today',
            'room' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'category' => 'required|string|max:255',
        ]);

        $this->assertFalse($validator->fails());
    }

    /**
     * Test that date validation accepts future dates
     */
    public function test_date_validation_accepts_future_dates(): void
    {
        $validator = Validator::make([
            'date' => Carbon::now()->addWeek()->format('Y-m-d'),
            'room' => 'A101',
            'capacity' => 10,
            'category' => 'TOEFL ITP'
        ], [
            'date' => 'required|date|after_or_equal:today',
            'room' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'category' => 'required|string|max:255',
        ]);

        $this->assertFalse($validator->fails());
    }
}
