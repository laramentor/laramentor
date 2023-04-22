<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mentor_id' => 1,
            'mentee_id' => 1,
            'uuid' => $this->faker->uuid,
            'name' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'start_date_time' => now(),
            'end_date_time' => now()->addHour(),
            'google_meeting_link' => $this->faker->url,
        ];
    }
}
