<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample faculties
        $facultyOfScience = Faculty::create(['name' => 'Faculty of Science']);
        $facultyOfEngineering = Faculty::create(['name' => 'Faculty of Engineering']);
        $facultyOfEconomics = Faculty::create(['name' => 'Faculty of Economics']);
        $facultyOfComputerScience = Faculty::create(['name' => 'Faculty of Computer Science']);

        // Create sample study programs
        StudyProgram::create([
            'name' => 'Mathematics',
            'level' => 'Undergraduate',
            'faculty_id' => $facultyOfScience->id
        ]);

        StudyProgram::create([
            'name' => 'Physics',
            'level' => 'Undergraduate',
            'faculty_id' => $facultyOfScience->id
        ]);

        StudyProgram::create([
            'name' => 'Computer Science',
            'level' => 'Undergraduate',
            'faculty_id' => $facultyOfEngineering->id
        ]);

        StudyProgram::create([
            'name' => 'Information Systems',
            'level' => 'Undergraduate',
            'faculty_id' => $facultyOfComputerScience->id
        ]);

        StudyProgram::create([
            'name' => 'Business Administration',
            'level' => 'Undergraduate',
            'faculty_id' => $facultyOfEconomics->id
        ]);
    }
}
