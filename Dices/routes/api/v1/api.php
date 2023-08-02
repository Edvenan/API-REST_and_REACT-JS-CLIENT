<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\GameController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes accessible by any user
Route::controller(UserController::class)->group(function () {
    Route::post('/login', 'login');     // User login (Admin / Player)
    Route::post('/players', 'register');    // Create Player
});

// Routes only accessible by authorized users with assigned roles
Route::middleware('auth:api', 'role')->group( function () {
    
    
    Route::middleware(['scope:admin,player'])->group( function () {
    
        Route::post('/logout', [UserController::class, 'logout']);  // User Logout
        Route::put('/players/{id}', [UserController::class, 'edit']);  // Edit a Player's name
        Route::get('/players/{id}/games', [UserController::class, 'listGames']);  // List a Player's games
        Route::delete('/players/{id}/games/', [GameController::class, 'destroy']);  // Delete a Player's Games

    });


    Route::middleware(['scope:player'])->group( function () {
    
        Route::post('/players/{id}/games/', [GameController::class, 'create']);  // Create Game (Roll the dices)

    });

    Route::middleware(['scope:admin'])->group( function () {
    
        Route::get('/players', [UserController::class, 'listPlayers']);  // List Players
        Route::get('/players/ranking', [UserController::class, 'ranking']);  // Ranking
        Route::get('/players/ranking/loser', [UserController::class, 'loser']);  // Ranking Loser
        Route::get('/players/ranking/winner', [UserController::class, 'winner']);  // Ranking Winner
        Route::delete('/players/{id}', [UserController::class, 'destroy']);  // Delete User
        
    });


});




/* 
Done 
-------
POST /players : crea un jugador/a.
PUT /players/{id} : modifica el nom del jugador/a.
GET /players/{id}/games: retorna el llistat de jugades per un jugador/a.
POST /players/{id}/games/ : un jugador/a específic realitza una tirada dels daus.
DELETE /players/{id}/games: elimina les tirades del jugador/a.
GET /players: retorna el llistat de tots els jugadors/es del sistema amb el seu percentatge mitjà d’èxits 
GET /players/ranking: retorna el rànquing mitjà de tots els jugadors/es del sistema. És a dir, el percentatge mitjà d’èxits.
GET /players/ranking/loser: retorna el jugador/a amb pitjor percentatge d’èxit.
GET /players/ranking/winner: retorna el jugador/a amb millor percentatge d’èxit.
DELETE /player/{id} : elimina un jugador, el seu rol i les seves jugades

Pending
----------
*/



/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */


