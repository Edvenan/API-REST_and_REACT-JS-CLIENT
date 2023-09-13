<?php

namespace Tests;

use App\Models\Game;
use App\Models\User;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use function PHPUnit\Framework\isNull;

class BaseTestCase extends TestCase
{
    const URL = "http://localhost:8000/api/v1";

    /**
     * Set up Passport OAuth Personal Access Clients
    */
    public function setUp(): void
    {
    parent::setUp();
    $this->artisan('passport:install');
    }

    /**
     * Helper function that creates a test user
     */
    public function createDummyUser($role = null)
    {
        // random role if no argument is received
        if(!isset($role)){
            $role = fake()->randomElement(['admin', 'player']);
        }
        // Only if role = 'player', create 3 test games
        if ($role == 'player'){
            $user = User::factory()->has(Game::factory(3))->create();
        } else {
            $user = User::factory()->create();
        }
        
        $user->role()->create(['role' => $role]);
        $scope = $user->role()->first()->role;
        return [$user, $scope];
    }
}