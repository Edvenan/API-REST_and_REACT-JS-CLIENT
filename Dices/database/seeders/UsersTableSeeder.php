<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\User;
use Database\Factories\RoleFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::create(['name' => 'SuperUser', 
                                'email' => 'admin@admin.com', 
                                'email_verified_at' => now(),
                                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                                'remember_token' => Str::random(10)])
            ->each(function($user) {
                $user->role()->create(['role' => 'admin']);
        });

        User::factory(10)->create()
        ->each(function($user) {
            $user->role()->save(\App\Models\Role::factory()->make());
        });

        Game::factory(100)->create();

    }
}
