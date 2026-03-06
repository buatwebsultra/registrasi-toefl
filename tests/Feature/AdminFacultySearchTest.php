<?php

namespace Tests\Feature;

use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFacultySearchTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function it_can_search_faculties_by_name()
    {
        Faculty::factory()->create(['name' => 'Fakultas Teknik']);
        Faculty::factory()->create(['name' => 'Fakultas Kedokteran']);
        Faculty::factory()->create(['name' => 'Fakultas Hukum']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.faculties.index', ['search' => 'Teknik']));

        $response->assertStatus(200);
        $response->assertSee('Fakultas Teknik');
        $response->assertDontSee('Fakultas Kedokteran');
        $response->assertDontSee('Fakultas Hukum');
    }

    /** @test */
    public function it_can_search_study_programs_by_name()
    {
        $faculty = Faculty::factory()->create(['name' => 'Fakultas Teknik']);
        StudyProgram::create([
            'name' => 'Teknik Informatika',
            'level' => 'S1',
            'passing_grade' => 450,
            'faculty_id' => $faculty->id
        ]);
        StudyProgram::create([
            'name' => 'Teknik Sipil',
            'level' => 'S1',
            'passing_grade' => 450,
            'faculty_id' => $faculty->id
        ]);
        StudyProgram::create([
            'name' => 'Program Spesial',
            'level' => 'S1',
            'passing_grade' => 450,
            'faculty_id' => $faculty->id
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.study-programs.index', ['search' => 'Informatika']));

        $response->assertStatus(200);
        $response->assertSee('Teknik Informatika');
        $response->assertDontSee('Teknik Sipil');
        $response->assertDontSee('Program Spesial');
    }

    /** @test */
    public function search_query_is_preserved_in_pagination()
    {
        Faculty::factory()->count(15)->create(['name' => 'Matching Faculty']);
        Faculty::factory()->create(['name' => 'Other']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.faculties.index', ['search' => 'Matching', 'page' => 2]));

        $response->assertStatus(200);
        $response->assertSee('Matching Faculty');
        $this->assertTrue(str_contains($response->getContent(), 'search=Matching'));
    }
}
