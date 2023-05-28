<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Mentor;
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
                $mentor = Mentor::factory()->make();
                $user->mentor()->save($mentor);
            });

        // create 3 users as mentees
        User::factory()->count(3)->create()
            ->each(function ($user) {
                $user->mentee()->create();
            });

        // create user as mentor (with factory) and output email
        $user = User::factory()->create();
        $user->mentor()->save(Mentor::factory()->make());

        $this->command->info('Mentor: ' . $user->email);

        // create user as mentee and output email
        $mentee = User::factory()->create()
            ->mentee()->create();

        $this->command->info('Mentee: ' . $mentee->user->email);

        // add sessions to mentors
        User::mentors()->with('mentor')->get()->each(function ($user) {
            Session::factory()->create([
                'mentor_id' => $user->mentor->id,
                'mentee_id' => User::mentees()->inRandomOrder()->first()->mentee->id,
            ]);
        });
    }
}
