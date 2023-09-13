<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\User;
use \App\Models\Role;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Admin user
        User::factory()
            ->create(['name' => 'SuperUser', 
            'email' => 'admin@admin.com'])
            ->role()->create(['role' => 'admin']);


        // Create Players and Games for each Player
        User::factory(10)
            ->has(Role::factory())
            ->has(Game::factory(10))
            ->create();

    }
}
