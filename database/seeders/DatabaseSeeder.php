<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('You can use the following users (with \'password\') to login to ' . config('app.name'));

        // create 3 users as mentors
        User::factory()->count(3)->create()
            ->each(function ($user) {
                $user->mentor()->create();
            });

        // create 3 users as mentees
        User::factory()->count(3)->create()
            ->each(function ($user) {
                $user->mentee()->create();
            });

        // create user as mentor and output email
        $mentor = User::factory()->create()
            ->mentor()->create();

        $this->command->info('Mentor: ' . $mentor->user->email);

        // create user as mentee and output email
        $mentee = User::factory()->create()
            ->mentee()->create();

        $this->command->info('Mentee: ' . $mentee->user->email);

        // add sessions to mentors
        User::isMentor()->get()->each(function ($user) {
            Session::factory()->create([
                'mentor_id' => $user->mentor->id,
                'mentee_id' => User::isMentee()->inRandomOrder()->first()->mentee->id,
            ]);
        });
    }
}
