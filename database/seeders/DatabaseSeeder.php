<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Mentor;
use App\Models\Session;
use App\Models\Skill;
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
        User::factory()->count(3)
            ->has(Mentor::factory()
                ->hasAttached(Skill::factory()->count(3)))
            ->create();

        // create 3 users as mentees
        User::factory()->count(3)
            ->hasMentee()
            ->create();

        // create user as mentor and output email
        $user = User::factory()
            ->hasMentor()
            ->create();

        $this->command->info('Mentor: ' . $user->email);

        // create user as mentee and output email
        $user = User::factory()
            ->hasMentee()
            ->create();

        $this->command->info('Mentee: ' . $user->email);

        // add sessions to mentors
        User::mentors()->with('mentor')->get()->each(function ($user) {
            Session::factory()->create([
                'mentor_id' => $user->mentor->id,
                'mentee_id' => User::mentees()->inRandomOrder()->first()->mentee->id,
            ]);
        });
    }
}
