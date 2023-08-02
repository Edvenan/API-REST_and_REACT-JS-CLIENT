<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\GameController;

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
    Route::post('/login', 'login')->name('login');
    Route::post('/players', 'register')->name('register');
});

// Routes accessible by authorized users with assigned roles
Route::middleware('auth:api', 'role')->group( function () {
    
    
    Route::middleware(['scope:admin,player'])->group( function () {
    
        Route::post('/logout', [UserController::class, 'logout'])->name('logout');  // Logout
        Route::put('/players/{id}', [UserController::class, 'edit'])->name('edit');  // Edit User name
        Route::get('/players/{id}/games', [UserController::class, 'listGames'])->name('listGames');  // List User games

    });


    Route::middleware(['scope:player'])->group( function () {
    
        Route::post('/players/{id}/games/', [GameController::class, 'create'])->name('create');  // Create Game (Throw dices)


    });

    // Route::resource('games', GameController::class);
});




/* 
Done 
-------
POST /players : crea un jugador/a.
PUT /players/{id} : modifica el nom del jugador/a.

Pending
----------
POST /players/{id}/games/ : un jugador/a específic realitza una tirada dels daus.
DELETE /players/{id}/games: elimina les tirades del jugador/a.
GET /players: retorna el llistat de tots els jugadors/es del sistema amb el seu percentatge mitjà d’èxits 
GET /players/{id}/games: retorna el llistat de jugades per un jugador/a.
GET /players/ranking: retorna el rànquing mitjà de tots els jugadors/es del sistema. És a dir, el percentatge mitjà d’èxits.
GET /players/ranking/loser: retorna el jugador/a amb pitjor percentatge d’èxit.
GET /players/ranking/winner: retorna el jugador/a amb millor percentatge d’èxit.

DELETE PLAYER (Admin)
 */



/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */


