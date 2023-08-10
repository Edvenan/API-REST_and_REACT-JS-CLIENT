<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTestCase;

class AuthorizationTest extends BaseTestCase
{
    use RefreshDatabase;

    // Test case suite to test User Registration, Login and Logout

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

        $response->dump()->assertStatus(201)->assertSee('created_at');
    }
    /** @test */
    public function a_user_registers_with_different_confirmed_password(): void
    {
        $response = $this->post(self::URL .'/players', [
            'name' => 'Mr. Fullstack',
            'email' => 'fullstack@sample.com',
            'password' => 'password',
            'c_password' => 'different_password',
        ]);

        $response->assertBadRequest();
    }
    /** @test */
    public function a_user_registers_without_name_and_gets_anonymous(): void
    {
        $response = $this->post(self::URL .'/players', [
            'email' => 'fullstack@sample.com',
            'password' => 'password',
            'c_password' => 'password',
        ]);

        $response->dump()->assertSuccessful()->assertJsonPath('data.name', 'Anonymous');
    }
    /** @test */
    public function two_users_register_with_blank_names_and_both_get_anonymous(): void
    {
        $response = $this->post(self::URL .'/players', [
            'name' => '',
            'email' => 'fullstack@sample.com',
            'password' => 'password',
            'c_password' => 'password',
        ]);
        $response->dump()->assertSuccessful()->assertJsonPath('data.name', 'Anonymous');

        $response = $this->post(self::URL .'/players', [
            'email' => 'php@sample.com',
            'password' => 'password',
            'c_password' => 'password',
        ]);

        $response->dump()->assertSuccessful()->assertJsonPath('data.name', 'Anonymous');
    }
    /** @test */
    public function a_user_registers_with_same_name_as_another_user(): void
    {
        $response = $this->post(self::URL .'/players', [
            'name' => 'Mr Dummy Name',
            'email' => 'fullstack@sample.com',
            'password' => 'password',
            'c_password' => 'password',
        ]);
        $response->assertSuccessful();

        $response = $this->post(self::URL .'/players', [
            'name' => 'Mr Dummy Name',
            'email' => 'another_email@sample.com',
            'password' => 'password',
            'c_password' => 'password',
        ]);

        $response->dump()->assertBadRequest()->assertSee('The name has already been taken.');
    }
    /** @test */
    public function a_user_registers_with_same_email_as_another_user(): void
    {
        $response = $this->post(self::URL .'/players', [
            'name' => 'Mr Dummy Name',
            'email' => 'fullstack@sample.com',
            'password' => 'password',
            'c_password' => 'password',
        ]);
        $response->assertSuccessful();

        $response = $this->post(self::URL .'/players', [
            'name' => 'Mr Another Dummy Name',
            'email' => 'fullstack@sample.com',
            'password' => 'password',
            'c_password' => 'password',
        ]);

        $response->dump()->assertBadRequest()->assertSee('The email has already been taken.');
    }

    /**
     * Test case to test user login [POST/login]
    */
    /** @test */
    public function a_user_logs_in_successfully(): void
    {
        [$user, $scope] = $this->createDummyUser();
        $credentials = ['email' => $user->email, 'password' => 'password'];

        $response = $this->post(self::URL.'/login', $credentials);

        $response->assertStatus(201)
                 ->assertJsonPath('data.token', fn (string $token) => strlen($token) >= 3);
        $this->assertArrayHasKey('token', $response['data']);
        $this->assertCredentials( $credentials, $guard = 'api');
    }
    /** @test */
    public function a_user_logs_in_with_wrong_passowrd(): void
    {
        [$user, $scope] = $this->createDummyUser();
        $credentials = ['email' => $user->email, 'password' => 'wrong_password'];

        $response = $this->post(self::URL.'/login', $credentials);

        $response->assertUnauthorized();
        $this->assertInvalidCredentials($credentials, $guard = 'api');
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
    public function a_user_logs_out_with_invalid_token(): void
    {
        [$user, $scope] = $this->createDummyUser();
        
        // Run a specific seeder...
        //$this->seed(UsersTableSeeder::class);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.'wrong.token.',
        ])->post(self::URL.'/logout');

        $response->assertRedirect();
    }
}
