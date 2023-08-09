<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test case suite to test User Registration, Login and Logout
     */

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
     * Test cases to test user creation/registration [POST/players]
    */
    /** @test */
    public function a_user_registers_successfully(): void
    {
        $response = $this->post(self::URL .'/players', [
            'name' => 'Mr. Fullstack',
            'email' => 'fullstack@sample.com',
            'password' => 'password',
            'c_password' => 'password',
        ]);

        $response->assertStatus(201);
    }
    /** @test */
    public function a_user_registers_unsuccessfully(): void
    {
        $response = $this->post(self::URL .'/players', [
            'name' => 'Mr. Fullstack',
            'email' => 'fullstack@sample.com',
            'password' => 'password',
            'c_password' => 'different_password',
        ]);

        $response->assertBadRequest();
    }
    /**
     * Test case to test user login [POST/login]
    */
    /** @test */
    public function a_user_logs_in_successfully(): void
    {
        [$user, $scope] = $this->createDummyUser();

        $response = $this->post(self::URL.'/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.token', fn (string $token) => strlen($token) >= 3);
        $this->assertArrayHasKey('token', $response['data']);

    }
    /** @test */
    public function a_user_logs_in_unsuccessfully(): void
    {
        [$user, $scope] = $this->createDummyUser();

        $response = $this->post(self::URL.'/login', [
            'email' => $user->email,
            'password' => 'wrong_password'
        ]);

        $response->assertUnauthorized();

    }
    /**
     * Test case to test user logout [POST/logout]
    */
    /** @test */
    public function a_user_logs_out_successfully(): void
    {
        [$user, $scope] = $this->createDummyUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,
        ])->post(self::URL.'/logout');

        $response->assertStatus(201);
    }
    /** @test */
    public function a_user_logs_out_unsuccessfully(): void
    {
        [$user, $scope] = $this->createDummyUser();
        
        // Run a specific seeder...
        //$this->seed(UsersTableSeeder::class);
        //$response->dump();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.'wrong.token.',
        ])->post(self::URL.'/logout');

        $response->assertRedirect();
    }

    //---------------------------------------------------------//

    /**
     * Helper function that creates a test user
     */
    public function createDummyUser()
    {
        $user = User::factory()->create();
        $user->role()->create(['role' => fake()->randomElement(['admin', 'player'])]);
        $scope = $user->role()->first()->role;
        return [$user, $scope];
    }
}
