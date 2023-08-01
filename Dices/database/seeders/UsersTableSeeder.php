<?php

namespace Database\Seeders;

use Database\Factories\RoleFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory(3)->create()
        ->each(function($user) {
            $user->role()->save(\App\Models\Role::factory()->make());
        });

    }
}
