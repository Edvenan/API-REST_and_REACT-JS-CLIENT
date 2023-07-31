<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserController;

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

Route::controller(UserController::class)->group(function () {
    Route::post('/login', 'login')->name('login');             // User Login route
    Route::post('/register', 'register')->name('register');    // User Register route
    Route::post('/logout', 'logout')->name('logout');          // User Logout route
    Route::get('/index', 'index')->name('index');              // Dummy route
});

Route::middleware('auth:api')->group( function () {
    Route::resource('games', GameController::class);
});




/* 
POST /players : crea un jugador/a.
PUT /players/{id} : modifica el nom del jugador/a.
POST /players/{id}/games/ : un jugador/a específic realitza una tirada dels daus.
DELETE /players/{id}/games: elimina les tirades del jugador/a.
GET /players: retorna el llistat de tots els jugadors/es del sistema amb el seu percentatge mitjà d’èxits 
GET /players/{id}/games: retorna el llistat de jugades per un jugador/a.
GET /players/ranking: retorna el rànquing mitjà de tots els jugadors/es del sistema. És a dir, el percentatge mitjà d’èxits.
GET /players/ranking/loser: retorna el jugador/a amb pitjor percentatge d’èxit.
GET /players/ranking/winner: retorna el jugador/a amb millor percentatge d’èxit.
 */



/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */


