<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Schedule;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminScheduleFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that delete schedule confirmation modal is displayed properly
     */
    public function test_delete_schedule_confirmation_modal(): void
    {
        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create schedule
        $schedule = Schedule::factory()->create([
            'room' => 'A101',
            'status' => 'available',
            'capacity' => 10,
            'used_capacity' => 0,
        ]);

        // Login and access dashboard
        $response = $this->actingAs($adminUser, 'web')
                         ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('A101'); // Should see room name
        $response->assertSee('Hapus'); // Should see delete button
    }

    /**
     * Test that clear participants functionality works
     */
    public function test_clear_schedule_participants_functionality(): void
    {
        // Create admin user
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create schedule
        $schedule = Schedule::factory()->create([
            'room' => 'B201',
            'status' => 'available',
            'capacity' => 10,
            'used_capacity' => 2,
        ]);

        // Create participants for schedule
        $faculty = \App\Models\Faculty::factory()->create();
        $studyProgram = \App\Models\StudyProgram::factory()->create(['faculty_id' => $faculty->id]);

        Storage::fake('public');
        $photo = UploadedFile::fake()->image('photo.jpg');
        $photoPath = $photo->store('photos', 'public');
        $proof = UploadedFile::fake()->image('payment.jpg');
        $proofPath = $proof->store('payment_proofs', 'public');
        $ktp = UploadedFile::fake()->image('ktp.jpg');
        $ktpPath = $ktp->store('ktps', 'public');

        $participant1 = \App\Models\Participant::create([
            'schedule_id' => $schedule->id,
            'seat_number' => 'B-01',
            'status' => 'confirmed',
            'nim' => '1234567890',
            'name' => 'Test User 1',
            'gender' => 'male',
            'birth_place' => 'Jakarta',
            'birth_date' => '1990-01-01',
            'email' => 'test1@example.com',
            'major' => 'Computer Science',
            'faculty' => 'Engineering',
            'phone' => '1234567890',
            'payment_date' => '2023-01-01',
            'test_category' => 'TOEFL ITP',
            'payment_proof_path' => $proofPath,
            'photo_path' => $photoPath,
            'ktp_path' => $ktpPath,
            'study_program_id' => $studyProgram->id,
            'faculty_id' => $faculty->id,
            'username' => 'testuser1',
            'password' => bcrypt('password'),
        ]);

        $participant2 = \App\Models\Participant::create([
            'schedule_id' => $schedule->id,
            'seat_number' => 'B-02',
            'status' => 'confirmed',
            'nim' => '0987654321',
            'name' => 'Test User 2',
            'gender' => 'female',
            'birth_place' => 'Bandung',
            'birth_date' => '1991-01-01',
            'email' => 'test2@example.com',
            'major' => 'Mathematics',
            'faculty' => 'Science',
            'phone' => '0987654321',
            'payment_date' => '2023-01-02',
            'test_category' => 'TOEFL CBT',
            'payment_proof_path' => $proofPath,
            'photo_path' => $photoPath,
            'ktp_path' => $ktpPath,
            'study_program_id' => $studyProgram->id,
            'faculty_id' => $faculty->id,
            'username' => 'testuser2',
            'password' => bcrypt('password'),
        ]);

        // Verify participants exist
        $this->assertCount(2, $schedule->fresh()->participants);

        // Visit the participants list page first to establish session
        $this->actingAs($adminUser, 'web')
             ->get(route('admin.participants.list', $schedule->id));

        // Test the clear participants function
        $response = $this->actingAs($adminUser, 'web')
                         ->post(route('admin.schedule.clear-participants', $schedule->id), [
                             '_token' => csrf_token(),
                         ]);

        $response->assertRedirect(route('admin.participants.list', $schedule->id));

        // Verify participants were deleted
        $this->assertCount(0, $schedule->fresh()->participants);

        // Verify schedule capacity is reset
        $this->assertEquals(0, $schedule->fresh()->used_capacity);
        $this->assertEquals('available', $schedule->fresh()->status);
    }
}
