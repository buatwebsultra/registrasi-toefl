<?php

namespace Tests\Feature;

use App\Models\Participant;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRescheduleParticipantTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $oldSchedule;
    protected $newSchedule;
    protected $participant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->oldSchedule = Schedule::factory()->create([
            'date' => now()->addDays(5),
            'capacity' => 10,
            'used_capacity' => 1,
            'status' => 'available',
        ]);

        $this->newSchedule = Schedule::factory()->create([
            'date' => now()->addDays(10),
            'capacity' => 10,
            'used_capacity' => 0,
            'status' => 'available',
        ]);

        $this->participant = Participant::factory()->create([
            'schedule_id' => $this->oldSchedule->id,
            'status' => 'confirmed',
            'seat_number' => '1',
        ]);
    }

    public function test_admin_can_reschedule_participant()
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.participant.reschedule', $this->participant->id), [
                'new_schedule_id' => $this->newSchedule->id,
            ]);

        $response->assertRedirect(route('admin.participants.list', $this->oldSchedule->id));
        $response->assertSessionHas('success');

        $this->participant->refresh();
        $this->assertEquals($this->newSchedule->id, $this->participant->schedule_id);
        $this->assertNotNull($this->participant->seat_number);

        $this->oldSchedule->refresh();
        $this->newSchedule->refresh();
        $this->assertEquals(0, $this->oldSchedule->used_capacity);
        $this->assertEquals(1, $this->newSchedule->used_capacity);
    }

    public function test_reschedule_fails_if_new_schedule_is_full()
    {
        $this->newSchedule->update([
            'used_capacity' => 10,
            'status' => 'full',
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.participants.list', $this->oldSchedule->id))
            ->put(route('admin.participant.reschedule', $this->participant->id), [
                'new_schedule_id' => $this->newSchedule->id,
            ]);

        $response->assertRedirect(route('admin.participants.list', $this->oldSchedule->id));
        $response->assertSessionHas('error', 'Jadwal yang dipilih sudah penuh.');

        $this->participant->refresh();
        $this->assertEquals($this->oldSchedule->id, $this->participant->schedule_id);
    }

    public function test_operator_can_also_reschedule()
    {
        $operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);

        $response = $this->actingAs($operator)
            ->put(route('admin.participant.reschedule', $this->participant->id), [
                'new_schedule_id' => $this->newSchedule->id,
            ]);

        $response->assertSessionHas('success');
    }

    public function test_superadmin_can_reschedule_to_full_schedule()
    {
        $superadmin = User::factory()->create(['role' => User::ROLE_SUPERADMIN]);

        $this->newSchedule->update([
            'used_capacity' => 10,
            'status' => 'full',
        ]);

        $response = $this->actingAs($superadmin)
            ->put(route('admin.participant.reschedule', $this->participant->id), [
                'new_schedule_id' => $this->newSchedule->id,
            ]);

        $response->assertSessionHas('success');

        $this->participant->refresh();
        $this->assertEquals($this->newSchedule->id, $this->participant->schedule_id);
    }

    public function test_unauthorized_user_cannot_reschedule()
    {
        // Role is implicitly checked by 'operator' middleware which blocks non-admin/non-operator users
        // Participants/guests should be redirected or aborted.

        $response = $this->put(route('admin.participant.reschedule', $this->participant->id), [
            'new_schedule_id' => $this->newSchedule->id,
        ]);

        $response->assertRedirect('/admin/login');
    }
}
