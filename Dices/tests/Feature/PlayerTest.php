<?php

namespace Tests\Feature;

use App\Models\Game;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTestCase;

class PlayerTest extends BaseTestCase
{
    use RefreshDatabase;

    // Test case suite to test a Player's functionality

    /**
     * Test cases to test player rolling the dices (game creation) [POST/players/{id}/games]
    */
    /** @test */
    public function a_player_rolls_its_dices_successfully(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->post(self::URL .'/players/'.$user->id.'/games');

        $response->assertSuccessful();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function  a_player_rolls_someone_elses_dices(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        // Sending another User Id in http request 
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->post(self::URL .'/players/'.($user->id+1).'/games');

        $response->assertForbidden();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function  a_player_rolls_its_dices_with_invalid_token(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        // Sending another User Id in http request 
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.'Wrong.Token.'])
            ->post(self::URL .'/players/'.$user->id.'/games');

        $response->assertRedirect();
        $this->assertGuest($guard = 'api');
    }

    /**
     * Test cases to test player listing games [GET/players/{id}/games]
    */
    /** @test */
    public function a_player_lists_its_games_successfully(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->get(self::URL .'/players/'.$user->id.'/games');

        $response->dump()->assertSuccessful()->assertSee('WinsRate');
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_player_lists_someone_elses_games(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->get(self::URL .'/players/'.($user->id+1).'/games');

        $response->dump()->assertForbidden();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_player_lists_its_games_with_invalid_token(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.'Wrong.Token.'])
            ->get(self::URL .'/players/'.$user->id.'/games');

        $response->dump()->assertRedirect();
        $this->assertGuest($guard = 'api');
    }

    /**
     * Test cases to test player editing its name [PUT/players/{id}]
    */
    /** @test */
    public function a_player_edits_its_name_successfully(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->put(self::URL .'/players/'.$user->id, ['name' => 'New Dummy Name']);

        $response->dump()->assertSuccessful()->assertJsonPath('data.name', 'New Dummy Name');
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_player_edits_its_name_leaving_it_blank_and_gets_anonymous(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->put(self::URL .'/players/'.$user->id, ['name' => '']);

        $response->dump()->assertSuccessful()->assertJsonPath('data.name', 'Anonymous');
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_player_edits_its_name_leaving_it_unchanged(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->put(self::URL .'/players/'.$user->id, ['name' => $user->name]);

        $response->dump()->assertBadRequest();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_player_edits_someone_elses_name(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->put(self::URL .'/players/'.($user->id+1), ['name' => 'New Dummy Name']);

        $response->dump()->assertForbidden();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_player_edits_email_instead_of_name(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->put(self::URL .'/players/'.$user->id, ['email' => 'Dummy@email.com']);

        $response->dump()->assertBadRequest();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_user_edits_its_name_with_invalid_token(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.'Wrong.Token.'])
            ->put(self::URL .'/players/'.$user->id, ['name' => 'New Dummy Name']);

        $response->dump()->assertRedirect();
        $this->assertGuest($guard = 'api');
    }

    /**
     * Test cases to test player deleting all games [DELETE/players/{id}/games]
    */
    /** @test */
    public function a_player_deletes_its_games_successfully(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->delete(self::URL .'/players/'.$user->id.'/games', []);

        $response->dump()->assertSuccessful();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_player_deletes_someone_elses_games(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->delete(self::URL .'/players/'.($user->id+1).'/games', []);

        $response->dump()->assertForbidden();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_player_with_no_games_deletes_its_games(): void
    {
        [$user, $scope] = $this->createDummyUser('player');
        Game::destroy($user->games);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->delete(self::URL .'/players/'.$user->id.'/games', []);

        $response->dump()->assertSuccessful();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_player_deletes_its_games_with_invalid_token(): void
    {
        [$user, $scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.'Wrong.Token.'])
            ->delete(self::URL .'/players/'.$user->id.'/games', []);

        $response->dump()->assertRedirect();
        $this->assertGuest($guard = 'api');
    }

    /**
     * Test cases to test player trying to access unauthorized routes
    */
    /** @test */
    public function a_player_lists_all_players_and_respective_wins_rate(): void
    {
        [$player, $player_scope] = $this->createDummyUser('player');
        // Create some players
        $this->seed(UsersTableSeeder::class);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$player->createToken('TestToken',[$player_scope])->accessToken,])
            ->get(self::URL .'/players/');

        $response->assertForbidden();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_player_lists_the_ranking(): void
    {
        [$player, $player_scope] = $this->createDummyUser('player');
        // Create some players
        $this->seed(UsersTableSeeder::class);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$player->createToken('TestToken',[$player_scope])->accessToken,])
            ->get(self::URL .'/players/ranking');

        $response->assertForbidden();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_player_lists_the_loser(): void
    {
        [$player, $player_scope] = $this->createDummyUser('player');
        // Create some players
        $this->seed(UsersTableSeeder::class);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$player->createToken('TestToken',[$player_scope])->accessToken,])
            ->get(self::URL .'/players/ranking/loser');

        $response->assertForbidden();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_player_lists_the_winner(): void
    {
        [$player, $player_scope] = $this->createDummyUser('player');
        // Create some players
        $this->seed(UsersTableSeeder::class);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$player->createToken('TestToken',[$player_scope])->accessToken,])
            ->get(self::URL .'/players/ranking/winner');

        $response->assertForbidden();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function a_player_deletes_a_player(): void
    {
        [$player, $player_scope] = $this->createDummyUser('player');
        // Create some players
        $this->seed(UsersTableSeeder::class);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$player->createToken('TestToken',[$player_scope])->accessToken,])
            ->delete(self::URL .'/players/'.'5', []);

        $response->assertForbidden();
        $this->assertAuthenticated($guard = 'api');
    }
}