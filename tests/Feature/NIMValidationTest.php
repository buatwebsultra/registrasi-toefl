<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Schedule;
use App\Models\Participant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class NIMValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that NIM cannot be reused for registration
     */
    public function test_nim_cannot_be_reused_for_registration(): void
    {
        // Buat data yang diperlukan (termasuk faculty dan study program)
        $faculty = \App\Models\Faculty::factory()->create();
        $studyProgram = \App\Models\StudyProgram::factory()->create(['faculty_id' => $faculty->id]);

        // Buat jadwal untuk uji coba
        $schedule = Schedule::factory()->create([
            'status' => 'available',
            'capacity' => 10,
            'used_capacity' => 0,
        ]);

        // File dummy untuk upload
        Storage::fake('public');
        $paymentProof = UploadedFile::fake()->image('payment_proof.jpg');
        $photo = UploadedFile::fake()->image('photo.jpg');
        $ktp = UploadedFile::fake()->image('ktp.jpg');

        // Data pendaftaran pertama
        $firstRegistrationData = [
            'schedule_id' => $schedule->id,
            'nim' => '1234567890',
            'name' => 'Test User',
            'gender' => 'male',
            'birth_place' => 'Jakarta',
            'birth_date' => '1990-01-01',
            'email' => 'test@example.com',
            'faculty_id' => $faculty->id,
            'study_program_id' => $studyProgram->id,
            'phone' => '1234567890',
            'payment_date' => '2023-01-01',
            'test_category' => 'TOEFL ITP',
            'payment_proof' => $paymentProof,
            'photo' => $photo,
            'ktp' => $ktp,
            'username' => 'testuser',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Test secara langsung melalui controller untuk menghindari masalah CSRF
        $participantController = new \App\Http\Controllers\ParticipantController();

        // Simulasi request pertama
        $request1 = \Illuminate\Http\Request::create('/participant/register', 'POST', $firstRegistrationData);
        $request1->files->set('payment_proof', $paymentProof);
        $request1->files->set('photo', $photo);
        $request1->files->set('ktp', $ktp);

        $response1 = $participantController->register($request1);
        $this->assertDatabaseHas('participants', ['nim' => '1234567890']);

        // Registrasi kedua dengan NIM yang sama harus gagal karena validasi di controller
        $secondRegistrationData = [
            'schedule_id' => $schedule->id,
            'nim' => '1234567890', // NIM yang sama
            'name' => 'Test User 2',
            'gender' => 'female',
            'birth_place' => 'Bandung',
            'birth_date' => '1991-01-01',
            'email' => 'test2@example.com',
            'faculty_id' => $faculty->id,
            'study_program_id' => $studyProgram->id,
            'phone' => '0987654321',
            'payment_date' => '2023-01-02',
            'test_category' => 'TOEFL ITP',
            'payment_proof' => $paymentProof,
            'photo' => $photo,
            'ktp' => $ktp,
            'username' => 'testuser2',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Test secara langsung melalui controller untuk registrasi kedua
        $request2 = \Illuminate\Http\Request::create('/participant/register', 'POST', $secondRegistrationData);
        $request2->files->set('payment_proof', $paymentProof);
        $request2->files->set('photo', $photo);
        $request2->files->set('ktp', $ktp);

        $response2 = $participantController->register($request2);

        // Response harus redirect kembali karena error
        $this->assertTrue(session()->has('errors'));
        $this->assertTrue(session()->get('errors')->has('nim')); // Harus ada error pada NIM

        // Harus tetap hanya satu entry dengan NIM tersebut di database
        $this->assertEquals(1, Participant::where('nim', '1234567890')->count());
    }

    /**
     * Test that duplicate NIM cannot be registered through direct database insertion
     */
    public function test_database_unique_constraint_on_nim(): void
    {
        // Buat data yang diperlukan
        $faculty = \App\Models\Faculty::factory()->create();
        $studyProgram = \App\Models\StudyProgram::factory()->create(['faculty_id' => $faculty->id]);
        $schedule = Schedule::factory()->create([
            'status' => 'available',
            'capacity' => 10,
            'used_capacity' => 0,
        ]);

        // Buat participant pertama
        $participant1 = Participant::create([
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
            'photo_path' => 'test/path.jpg',
            'ktp_path' => 'test/path.jpg',
            'study_program_id' => $studyProgram->id,
            'faculty_id' => $faculty->id,
            'username' => 'testuser',
            'password' => bcrypt('password'),
        ]);

        // Mencoba membuat participant kedua dengan NIM yang sama harus gagal
        $this->expectException(\Illuminate\Database\QueryException::class);

        Participant::create([
            'schedule_id' => $schedule->id,
            'seat_number' => 'A-02',
            'status' => 'confirmed',
            'nim' => '1234567890', // NIM yang sama
            'name' => 'Test User 2',
            'gender' => 'female',
            'birth_place' => 'Bandung',
            'birth_date' => '1991-01-01',
            'email' => 'test2@example.com',
            'major' => 'Computer Science',
            'faculty' => 'Engineering',
            'phone' => '0987654321',
            'payment_date' => '2023-01-02',
            'test_category' => 'TOEFL ITP',
            'payment_proof_path' => 'test/path2.jpg',
            'photo_path' => 'test/path2.jpg',
            'ktp_path' => 'test/path2.jpg',
            'study_program_id' => $studyProgram->id,
            'faculty_id' => $faculty->id,
            'username' => 'testuser2',
            'password' => bcrypt('password'),
        ]);
    }
}
