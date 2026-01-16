<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Schedule;
use App\Models\User;

class AdminMarkScheduleFullTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that admin can mark schedule as full
     */
    public function test_admin_can_mark_schedule_as_full(): void
    {
        // Create schedule data
        $schedule = Schedule::factory()->create([
            'status' => 'available',
            'capacity' => 10,
            'used_capacity' => 8,
        ]);

        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Visit the dashboard page first to establish session (this will load CSRF token)
        $this->actingAs($adminUser, 'web')
             ->get('/admin/dashboard');

        // Test that the mark-full route works with POST request
        $response = $this->withSession(['_token' => csrf_token()])
                         ->actingAs($adminUser, 'web')
                         ->post("/admin/schedule/{$schedule->id}/mark-full", ['_token' => csrf_token()]);

        // Should redirect back to dashboard
        $response->assertRedirect();

        // Refresh schedule from database
        $schedule->refresh();

        // Verify that schedule status is now 'full'
        $this->assertEquals('full', $schedule->status);
    }

    /**
     * Test that dashboard page contains the correct form for mark schedule as full
     */
    public function test_dashboard_contains_correct_mark_full_form(): void
    {
        // Create schedule data
        $schedule = Schedule::factory()->create([
            'status' => 'available',
            'capacity' => 10,
            'used_capacity' => 8,
        ]);

        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Request to admin dashboard page
        $response = $this->actingAs($adminUser, 'web')
                         ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');

        // Verify that the page has the form with POST method for mark-full
        $response->assertSee('method="POST"', false);
        $response->assertSee('action="'.route('admin.schedule.mark-full', $schedule->id).'"', false);
    }
}
