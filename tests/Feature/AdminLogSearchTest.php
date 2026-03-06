<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLogSearchTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Super Admin is needed for logs
        $this->admin = User::factory()->create(['role' => User::ROLE_SUPERADMIN]);
    }

    /** @test */
    public function it_can_search_logs_by_user_name()
    {
        ActivityLog::create([
            'user_id' => 1,
            'user_type' => 'admin',
            'user_name' => 'Budi Operator',
            'action' => 'Login',
            'description' => 'Operator login ke sistem',
            'ip_address' => '127.0.0.1'
        ]);

        ActivityLog::create([
            'user_id' => 2,
            'user_type' => 'admin',
            'user_name' => 'Ani Admin',
            'action' => 'Update Profil',
            'description' => 'Admin memperbarui profil',
            'ip_address' => '127.0.0.1'
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.logs.index', ['search' => 'Budi']));

        $response->assertStatus(200);
        $response->assertSee('Budi Operator');
        $response->assertDontSee('Ani Admin');
    }

    /** @test */
    public function it_can_search_logs_by_action()
    {
        ActivityLog::create([
            'user_id' => 1,
            'user_type' => 'admin',
            'user_name' => 'Budi Operator',
            'action' => 'Hapus Peserta',
            'description' => 'Menghapus peserta ID 10',
            'ip_address' => '127.0.0.1'
        ]);

        ActivityLog::create([
            'user_id' => 1,
            'user_type' => 'admin',
            'user_name' => 'Budi Operator',
            'action' => 'Akses Masuk',
            'description' => 'Masuk sukses',
            'ip_address' => '127.0.0.1'
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.logs.index', ['search' => 'Hapus']));

        $response->assertStatus(200);
        $response->assertSee('Hapus Peserta');
        $response->assertDontSee('Akses Masuk');
    }

    /** @test */
    public function it_can_search_logs_by_description()
    {
        ActivityLog::create([
            'user_id' => 1,
            'user_type' => 'admin',
            'user_name' => 'Budi Operator',
            'action' => 'Update',
            'description' => 'Mengecek item_unik baru',
            'ip_address' => '127.0.0.1'
        ]);

        ActivityLog::create([
            'user_id' => 1,
            'user_type' => 'admin',
            'user_name' => 'Budi Operator',
            'action' => 'Update',
            'description' => 'Mengubah data program',
            'ip_address' => '127.0.0.1'
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.logs.index', ['search' => 'item_unik']));

        $response->assertStatus(200);
        $response->assertSee('Mengecek item_unik baru');
        $response->assertDontSee('Mengubah data program');
    }

    /** @test */
    public function search_query_is_preserved_in_pagination()
    {
        for ($i = 0; $i < 60; $i++) {
            ActivityLog::create([
                'user_id' => 1,
                'user_type' => 'admin',
                'user_name' => "User $i",
                'action' => 'Action test',
                'description' => 'Matching log',
                'ip_address' => '127.0.0.1'
            ]);
        }

        $response = $this->actingAs($this->admin)
            ->get(route('admin.logs.index', ['search' => 'Matching', 'page' => 2]));

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->getContent(), 'search=Matching'));
    }
}
