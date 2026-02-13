<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Participant;
use App\Models\Schedule;
use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Support\Str;

class ParticipantFactory extends Factory
{
    protected $model = Participant::class;

    public function definition(): array
    {
        return [
            'schedule_id' => Schedule::factory(),
            'faculty_id' => Faculty::factory(),
            'study_program_id' => StudyProgram::factory(),
            'nim' => $this->faker->unique()->numberBetween(100000, 999999),
            'name' => $this->faker->name,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'birth_place' => $this->faker->city,
            'birth_date' => $this->faker->date(),
            'email' => $this->faker->unique()->safeEmail,
            'phone' => '0812' . $this->faker->numberBetween(1000000, 9999999),
            'major' => 'TEST MAJOR',
            'faculty' => 'TEST FACULTY',
            'payment_date' => now(),
            'test_category' => 'TOEFL PBT',
            'payment_proof_path' => 'payment_proofs/default.jpg',
            'photo_path' => 'photos/default.jpg',
            'ktp_path' => 'ktps/default.jpg',
            'username' => $this->faker->unique()->userName,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'status' => 'pending',
            'seat_status' => 'reserved',
        ];
    }
}
