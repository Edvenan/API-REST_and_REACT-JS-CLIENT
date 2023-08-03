<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $users = User::whereNot('name', 'SuperUser')->pluck('id')->toArray();
        $dice_1 = fake()->numberBetween(1, 6);
        $dice_2 = fake()->numberBetween(1, 6);
        $result = $dice_1 + $dice_2 == 7 ? 1 : 0;

        return [
            'user_id' => fake()->randomElement($users),
            'dice_1' => $dice_1,
            'dice_2' => $dice_2,
            'result' => $result,
        ];
    }
}
