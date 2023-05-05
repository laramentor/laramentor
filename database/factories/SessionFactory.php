<?php

namespace Database\Factories;

use Carbon\Carbon;
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
        $session_start_date_time = Carbon::parse(fake()->dateTimeBetween('now', '+1 week'));

        return [
            'mentor_id' => 1,
            'mentee_id' => 1,
            'uuid' => $this->faker->uuid,
            'name' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'start_date_time' => $session_start_date_time,
            'end_date_time' => $session_start_date_time->addHour(),
            'google_meeting_link' => $this->faker->url,
        ];
    }
}
