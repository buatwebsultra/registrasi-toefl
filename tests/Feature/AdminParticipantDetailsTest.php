<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Participant;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminParticipantDetailsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that admin participant details page renders correctly after button changes
     */
    public function test_admin_participant_details_page_renders(): void
    {
        // Buat data yang diperlukan
        $faculty = \App\Models\Faculty::factory()->create();
        $studyProgram = \App\Models\StudyProgram::factory()->create(['faculty_id' => $faculty->id]);
        $schedule = Schedule::factory()->create([
            'status' => 'available',
            'capacity' => 10,
            'used_capacity' => 0,
        ]);

        // File dummy untuk upload
        Storage::fake('public');
        $photo = UploadedFile::fake()->image('photo.jpg');
        $photoPath = $photo->store('photos', 'public');

        // Buat participant
        $participant = Participant::create([
            'schedule_id' => $schedule->id,
            'seat_number' => 'A-01',
            'status' => 'confirmed',
            'nim' => '1234567890',
            'name' => 'Test User',
            'gender' => 'male',
            'birth_place' => 'Jakarta',
            'birth_date' => '1990-01-01',
            'email' => 'test@example.com',
            'major' => 'Computer Science',
            'faculty' => 'Engineering',
            'phone' => '1234567890',
            'payment_date' => '2023-01-01',
            'test_category' => 'TOEFL ITP',
            'payment_proof_path' => 'test/path.jpg',
            'photo_path' => $photoPath,
            'ktp_path' => 'test/ktp.jpg',
            'study_program_id' => $studyProgram->id,
            'faculty_id' => $faculty->id,
            'username' => 'testuser',
            'password' => bcrypt('password'),
        ]);

        // Buat user admin
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Lakukan request ke route detail participant
        $response = $this->actingAs($adminUser, 'web')
                         ->get("/admin/participant/{$participant->id}/details");

        $response->assertStatus(200);
        $response->assertViewIs('admin.participant-details');
    }
}
