<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Schedule;
use App\Models\Participant;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CapacityEnforcementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that registration is rejected when schedule is full
     */
    public function test_registration_is_rejected_when_schedule_is_full(): void
    {
        Storage::fake('private');

        $faculty = Faculty::factory()->create();
        $studyProgram = StudyProgram::factory()->create(['faculty_id' => $faculty->id]);
        $schedule = Schedule::factory()->create([
            'capacity' => 1,
            'used_capacity' => 1,
            'status' => 'full',
            'date' => now()->addDays(5)->toDateString(),
        ]);

        $response = $this->post('/participant/register', [
            'nim' => '123456',
            'name' => 'John Doe',
            'gender' => 'male',
            'birth_place' => 'Jakarta',
            'birth_date' => '2000-01-01',
            'email' => 'john@example.com',
            'phone' => '08123456789',
            'test_category' => 'TOEFL PBT',
            'schedule_id' => $schedule->id,
            'faculty_id' => $faculty->id,
            'study_program_id' => $studyProgram->id,
            'username' => 'johndoe',
            'password' => 'SecurePassword@123',
            'password_confirmation' => 'SecurePassword@123',
            'payment_date' => now()->toDateString(),
            'payment_hour' => '10',
            'payment_minute' => '30',
            'payment_second' => '00',
            'payment_proof' => UploadedFile::fake()->image('payment.jpg'),
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'ktp' => UploadedFile::fake()->image('ktp.jpg'),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['schedule_id']);
        $this->assertEquals(1, $schedule->fresh()->used_capacity);
    }

    /**
     * Test that admin dashboard caps participant count at capacity
     */
    public function test_admin_dashboard_caps_participant_count_at_capacity(): void
    {
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $schedule = Schedule::factory()->create([
            'date' => now()->addDays(30)->toDateString(),
            'capacity' => 10,
            'used_capacity' => 11, // Manually setting over capacity to test display
        ]);

        // Creating actual participants to match participants_count if count() is used
        Participant::factory()->count(11)->create(['schedule_id' => $schedule->id]);

        $response = $this->actingAs($adminUser)->get('/admin/dashboard');

        $response->assertStatus(200);
        // It should see capped numbers. We use regex to handle potential whitespace/newlines in HTML
        $response->assertSee('10');
        $response->assertSee('/');
        $response->assertDontSee('11 / 10');
        $response->assertDontSee('11 /');
    }
}
