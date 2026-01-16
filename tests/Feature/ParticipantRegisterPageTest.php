<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Schedule;
use App\Models\Faculty;
use App\Models\StudyProgram;

class ParticipantRegisterPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that participant registration page renders correctly and has 'Daftar' button
     */
    public function test_participant_register_page_renders_with_daftar_button(): void
    {
        // Buat data yang diperlukan
        $faculty = Faculty::factory()->create();
        $studyProgram = StudyProgram::factory()->create(['faculty_id' => $faculty->id]);
        $schedule = Schedule::factory()->create([
            'status' => 'available',
            'capacity' => 10,
            'used_capacity' => 0,
        ]);

        // Lakukan request ke halaman register
        $response = $this->get('/participant/register');

        $response->assertStatus(200);
        $response->assertViewIs('participant.register');

        // Cek bahwa halaman mengandung tombol dengan teks 'Daftar'
        $response->assertSee('Daftar');
    }
}
