<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Schedule;
use App\Models\User;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that admin dashboard page renders correctly after removing download button
     */
    public function test_admin_dashboard_page_renders_without_download_button(): void
    {
        // Create schedule data
        $schedule = Schedule::factory()->create([
            'status' => 'available',
            'capacity' => 10,
            'used_capacity' => 0,
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

        // Verify that the page loads without errors
        $response->assertDontSee('Download Data Peserta'); // Ensuring the removed content is not present

        // Check that other essential elements are still present
        $response->assertSee('Dashboard');
        $response->assertSee('Buat Jadwal Baru');
        $response->assertSee('Kelola Program Akademik');
    }
}
