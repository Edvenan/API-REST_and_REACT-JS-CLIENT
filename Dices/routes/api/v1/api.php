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


// Redirected route when user token is not authorized
Route::get('/', function () {
    $response = [
        'success' => false,
        'message' => 'Unauthorized. Invalid token received. Please log in again.',
    ];

    // send 'unauthorized' response back to client
    return response()->json($response, 401);
})->name('login');



