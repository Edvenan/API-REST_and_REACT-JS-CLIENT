<?php

namespace Tests\Feature;

use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTestCase;

class AdminTest extends BaseTestCase
{
    use RefreshDatabase;

    // Test case suite to test an Admin's functionality

    /**
     * Test cases to test an adnmin listing a player's games [GET/players/{id}/games]
    */
    /** @test */
    public function an_admin_lists_a_players_games(): void
    {
        [$admin, $admin_scope] = $this->createDummyUser('admin');
        [$player, $player_scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$admin->createToken('TestToken',[$admin_scope])->accessToken,])
            ->get(self::URL .'/players/'.$player->id.'/games');

        $response->assertSuccessful()->assertSee('WinsRate');
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function an_admin_lists_a_non_existing_players_games(): void
    {
        [$admin, $admin_scope] = $this->createDummyUser('admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$admin->createToken('TestToken',[$admin_scope])->accessToken,])
            ->get(self::URL .'/players/'.'1000'.'/games');

        $response->assertNotFound();
        $this->assertAuthenticated($guard = 'api');
    }

    /**
     * Test cases to test an adnmin listing all players with respective wins rate [GET/players]
    */
    /** @test */
    public function an_admin_lists_all_players_and_respective_wins_rate(): void
    {
        [$admin, $admin_scope] = $this->createDummyUser('admin');
        // Create some players
        $this->seed(UsersTableSeeder::class);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$admin->createToken('TestToken',[$admin_scope])->accessToken,])
            ->get(self::URL .'/players/');

        $response->assertSuccessful()->assertSee('winsRate')->assertSee('avg_winsRate');
        $this->assertAuthenticated($guard = 'api');
    }
    
    /**
     * Test cases to test an adnmin listing the ranking [GET/players/ranking]
    */
    /** @test */
    public function an_admin_lists_the_ranking(): void
    {
        [$admin, $admin_scope] = $this->createDummyUser('admin');
        // Create some players
        $this->seed(UsersTableSeeder::class);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$admin->createToken('TestToken',[$admin_scope])->accessToken,])
            ->get(self::URL .'/players/ranking');

        $response->assertSuccessful()->assertSee('winsRate')->assertDontSee('avg_winsRate');
        $this->assertAuthenticated($guard = 'api');
    }

    /**
     * Test cases to test an adnmin listing the loser [GET/players/ranking/loser]
    */
    /** @test */
    public function an_admin_lists_the_loser(): void
    {
        [$admin, $admin_scope] = $this->createDummyUser('admin');
        // Create some players
        $this->seed(UsersTableSeeder::class);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$admin->createToken('TestToken',[$admin_scope])->accessToken,])
            ->get(self::URL .'/players/ranking/loser');

        $response->assertSuccessful()->assertSee('winsRate')->assertDontSee('avg_winsRate');
        $this->assertAuthenticated($guard = 'api');
    }

    /**
     * Test cases to test an adnmin listing the winner [GET/players/ranking/winner]
    */
    /** @test */
    public function an_admin_lists_the_winner(): void
    {
        [$admin, $admin_scope] = $this->createDummyUser('admin');
        // Create some players
        $this->seed(UsersTableSeeder::class);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$admin->createToken('TestToken',[$admin_scope])->accessToken,])
            ->get(self::URL .'/players/ranking/winner');

        $response->assertSuccessful()->assertSee('winsRate')->assertDontSee('avg_winsRate');
        $this->assertAuthenticated($guard = 'api');
    }

    /**
     * Test cases to test admin editing its and another's name [PUT/players/{id}]
    */
    /** @test */
    public function an_admin_edits_its_name_successfully(): void
    {
        [$user, $scope] = $this->createDummyUser('admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->put(self::URL .'/players/'.$user->id, ['name' => 'New Dummy Name']);

        $response->assertSuccessful()->assertJsonPath('data.name', 'New Dummy Name');
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function an_admin_edits_someone_elses_name(): void
    {
        [$admin, $admin_scope] = $this->createDummyUser('admin');
        [$player, $player_scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$admin->createToken('TestToken',[$admin_scope])->accessToken,])
            ->put(self::URL .'/players/'.$player->id, ['name' => 'New Dummy Name']);

        $response->assertSuccessful();
        $this->assertAuthenticated($guard = 'api');
    }

    /**
     * Test cases to test admin deleting a player's games [DELETE/players/{id}/games]
    */
    /** @test */
    public function an_admin_deletes_a_players_games(): void
    {
        [$admin, $admin_scope] = $this->createDummyUser('admin');
        // Create some players
        $this->seed(UsersTableSeeder::class);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$admin->createToken('TestToken',[$admin_scope])->accessToken,])
            ->delete(self::URL .'/players/'.'5'.'/games', []);

        $response->assertSuccessful();
        $this->assertAuthenticated($guard = 'api');
    }

    /**
     * Test cases to test admin deleting a player [DELETE/players/{id}]
    */
    /** @test */
    public function an_admin_deletes_a_player(): void
    {
        [$admin, $admin_scope] = $this->createDummyUser('admin');
        // Create some players
        $this->seed(UsersTableSeeder::class);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$admin->createToken('TestToken',[$admin_scope])->accessToken,])
            ->delete(self::URL .'/players/'.'5', []);

        $response->assertSuccessful();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function an_admin_deletes_itself(): void
    {
        [$admin, $admin_scope] = $this->createDummyUser('admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$admin->createToken('TestToken',[$admin_scope])->accessToken,])
            ->delete(self::URL .'/players/'.$admin->id, []);

        $response->assertForbidden();
        $this->assertAuthenticated($guard = 'api');
    }

    /**
     * Test cases to test Admin trying to access unauthorized/Forbidden routes
    */
    /** @test */
    public function an_admin_rolls_its_dices(): void
    {
        [$user, $scope] = $this->createDummyUser('admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('TestToken',[$scope])->accessToken,])
            ->post(self::URL .'/players/'.$user->id.'/games');

        $response->assertForbidden();
        $this->assertAuthenticated($guard = 'api');
    }
    /** @test */
    public function an_admin_rolls_someone_elses_dices(): void
    {
        [$admin, $admin_scope] = $this->createDummyUser('admin');
        [$player, $player_scope] = $this->createDummyUser('player');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$admin->createToken('TestToken',[$admin_scope])->accessToken,])
            ->post(self::URL .'/players/'.$player->id.'/games');

        $response->assertForbidden();
        $this->assertAuthenticated($guard = 'api');
    } 
}