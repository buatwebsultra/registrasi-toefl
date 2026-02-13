<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Participant;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RetakePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_participant_with_permission_status_can_see_retake_button(): void
    {
        $schedule = Schedule::factory()->create([
            'date' => now()->addDays(5)->toDateString(),
        ]);

        $participant = Participant::factory()->create([
            'schedule_id' => $schedule->id,
            'attendance' => 'permission',
            'status' => 'confirmed',
            'test_score' => null,
            'is_score_validated' => false,
            'username' => 'testuser',
            'password' => Hash::make('SecurePassword@123'),
        ]);

        // Login as participant
        $response = $this->post('/participant/login', [
            'username' => 'testuser',
            'password' => 'SecurePassword@123',
        ]);

        $response->assertRedirect('/participant/dashboard/' . $participant->id);

        $response = $this->get('/participant/dashboard/' . $participant->id);

        $response->assertStatus(200);
        $response->assertSee('Daftar Tes Ulang');
        $response->assertSeeHtml('Status kehadiran Anda adalah <strong>izin</strong>');
        $response->assertDontSee('Belum Bisa Daftar Ulang');
    }

    public function test_participant_with_permission_status_can_access_retake_form(): void
    {
        $schedule = Schedule::factory()->create([
            'date' => now()->addDays(5)->toDateString(),
        ]);

        $participant = Participant::factory()->create([
            'schedule_id' => $schedule->id,
            'attendance' => 'permission',
            'status' => 'confirmed',
            'test_score' => null,
            'is_score_validated' => false,
            'username' => 'testuser',
            'password' => Hash::make('SecurePassword@123'),
        ]);

        $this->post('/participant/login', [
            'username' => 'testuser',
            'password' => 'SecurePassword@123',
        ]);

        $response = $this->get('/participant/retake/' . $participant->id);

        $response->assertStatus(200);
        $response->assertSee('Formulir Pendaftaran Ulang TOEFL');
    }

    public function test_participant_with_permission_status_can_reschedule_themselves(): void
    {
        $oldSchedule = Schedule::factory()->create([
            'date' => now()->addDays(5)->toDateString(),
            'capacity' => 10,
            'used_capacity' => 1,
            'status' => 'available',
        ]);

        $newSchedule = Schedule::factory()->create([
            'date' => now()->addDays(10)->toDateString(),
            'capacity' => 10,
            'used_capacity' => 0,
            'status' => 'available',
            'category' => 'TOEFL PBT',
        ]);

        $participant = Participant::factory()->create([
            'schedule_id' => $oldSchedule->id,
            'attendance' => 'permission',
            'status' => 'confirmed',
            'test_score' => null,
            'is_score_validated' => false,
            'username' => 'testuser',
            'password' => Hash::make('SecurePassword@123'),
            'test_category' => 'TOEFL PBT',
        ]);

        $this->post('/participant/login', [
            'username' => 'testuser',
            'password' => 'SecurePassword@123',
        ]);

        $response = $this->post('/participant/reschedule/' . $participant->id, [
            'new_schedule_id' => $newSchedule->id,
        ]);

        $response->assertRedirect('/participant/dashboard/' . $participant->id);
        $response->assertSessionHas('success');

        $participant->refresh();
        $this->assertEquals($newSchedule->id, $participant->schedule_id);
        $this->assertNull($participant->attendance);

        $oldSchedule->refresh();
        $this->assertEquals(0, $oldSchedule->used_capacity);

        $newSchedule->refresh();
        $this->assertEquals(1, $newSchedule->used_capacity);
    }
}
