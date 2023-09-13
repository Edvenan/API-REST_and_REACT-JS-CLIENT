<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the Role model's states.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //'role' => fake()->randomElement(['admin', 'player'])
            'role' => 'player'
        ];
    }
}
