<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'room' => $this->faker->word() . '-' . $this->faker->randomNumber(2, true),
            'capacity' => $this->faker->numberBetween(20, 50),
            'used_capacity' => 0,
            'status' => 'available',
            'category' => $this->faker->randomElement(['TOEFL ITP', 'TOEFL iBT', 'TOEFL CBT']),
        ];
    }
}
