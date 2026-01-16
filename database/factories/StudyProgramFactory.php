<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudyProgram>
 */
class StudyProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' Study Program',
            'level' => $this->faker->randomElement(['bachelor', 'master', 'doctor']),
            'faculty_id' => \App\Models\Faculty::factory(), // Buat faculty jika tidak ada
        ];
    }
}
