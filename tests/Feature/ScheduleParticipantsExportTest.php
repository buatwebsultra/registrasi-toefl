<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Participant;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ScheduleParticipantsExportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that schedule participants export functionality works correctly
     */
    public function test_schedule_participants_export(): void
    {
        // Buat data yang diperlukan
        $faculty = \App\Models\Faculty::factory()->create();
        $studyProgram = \App\Models\StudyProgram::factory()->create(['faculty_id' => $faculty->id]);
        $schedule = Schedule::factory()->create([
            'status' => 'available',
            'capacity' => 10,
            'used_capacity' => 2,
        ]);

        // File dummy untuk upload
        Storage::fake('public');
        $photo = UploadedFile::fake()->image('photo.jpg');
        $photoPath = $photo->store('photos', 'public');

        // Buat participant untuk jadwal ini
        $participant1 = Participant::create([
            'schedule_id' => $schedule->id,
            'seat_number' => 'A-01',
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
            'payment_proof_path' => 'test/path1.jpg',
            'photo_path' => $photoPath,
            'ktp_path' => 'test/ktp1.jpg',
            'study_program_id' => $studyProgram->id,
            'faculty_id' => $faculty->id,
            'username' => 'testuser1',
            'password' => bcrypt('password'),
        ]);

        $participant2 = Participant::create([
            'schedule_id' => $schedule->id,
            'seat_number' => 'A-02',
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
            'payment_proof_path' => 'test/path2.jpg',
            'photo_path' => $photoPath,
            'ktp_path' => 'test/ktp2.jpg',
            'study_program_id' => $studyProgram->id,
            'faculty_id' => $faculty->id,
            'username' => 'testuser2',
            'password' => bcrypt('password'),
        ]);

        // Buat user admin
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Test export participants untuk jadwal tertentu
        $response = $this->actingAs($adminUser, 'web')
                         ->get("/admin/schedule/{$schedule->id}/participants/export");

        // Karena export menghasilkan file, responnya mungkin tidak bisa diprediksi dengan header
        // Tapi kita bisa cek bahwa route ditemukan dan tidak mengembalikan error 404
        $response->assertStatus(200);

        // Kita juga bisa cek bahwa schedule benar-benar ditemukan
        $this->assertNotNull($schedule);
        $this->assertEquals(2, $schedule->participants()->count());
    }
}
