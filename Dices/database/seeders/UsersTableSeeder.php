<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\User;
use \App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Admin user
        User::create(['name' => 'SuperUser', 
                    'email' => 'admin@admin.com', 
                    'email_verified_at' => now(),
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'remember_token' => Str::random(10)])
            ->each(function($user) {
                $user->role()->create(['role' => 'admin']);
        });


        // Create Players and Games for each Player
        User::factory(10)
            ->has(Role::factory())
            ->has(Game::factory(10))
            ->create();

    }
}
