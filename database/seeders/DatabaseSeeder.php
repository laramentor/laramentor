<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        // create user as mentor and output email
        $mentor = User::factory()->create()->mentor()->create();
        $this->command->info('Mentor: ' . $mentor->user->email);

        // create user as mentee and output email
        $user = User::factory()->create();
        $this->command->info('Mentee: ' . $user->email);
    }
}
