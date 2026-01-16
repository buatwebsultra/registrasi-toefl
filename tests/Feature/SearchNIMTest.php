<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Participant;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SearchNIMTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that NIM search functionality works correctly
     */
    public function test_nim_search_functionality(): void
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

        // Buat participant dengan NIM tertentu
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

        // Buat participant lain dengan NIM berbeda
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
            'test_category' => 'TOEFL ITP',
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

        // Test tanpa pencarian - semua participant harus muncul
        $response = $this->actingAs($adminUser, 'web')
                         ->get("/admin/schedule/{$schedule->id}/participants");

        $response->assertStatus(200);
        $response->assertViewHas('participants');
        $participants = $response->original->getData()['participants'];
        $this->assertCount(2, $participants);

        // Test dengan pencarian NIM yang ada
        $response = $this->actingAs($adminUser, 'web')
                         ->get("/admin/schedule/{$schedule->id}/participants?search_nim=1234567890");

        $response->assertStatus(200);
        $response->assertViewHas('searchNim', '1234567890');
        $participants = $response->original->getData()['participants'];
        $this->assertCount(1, $participants);
        $this->assertEquals('1234567890', $participants->first()->nim);

        // Test dengan pencarian NIM yang tidak ada
        $response = $this->actingAs($adminUser, 'web')
                         ->get("/admin/schedule/{$schedule->id}/participants?search_nim=1111111111");

        $response->assertStatus(200);
        $response->assertViewHas('searchNim', '1111111111');
        $participants = $response->original->getData()['participants'];
        $this->assertCount(0, $participants);
    }
}
