<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\api\v1\BaseController;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Create new Game
     */
    public function create($id)
    {
        // Validate if Auth user can act on the target id data
        if ( ! $this->userValidation($id) ){
            return $this->sendError("Not authorized to create another user's games.", ['Auth User id : '.Auth::user()->id, 'Target User id : '.$id], 401); 
        }

        // Validate if target user exists
        $user = User::find($id);

        if(!$user) {
            return $this->sendError("Can't create a game for this user. User not found.", "Target User id = ".$id, 404);       
        }

        // roll the dices
        $dice_1 = random_int(1,6);
        $dice_2 = random_int(1,6);
        
        // obtain result (wins = 1, loses = 0)
        $sum = $dice_1 + $dice_2;

        if ($sum == 7){
            $result = 1;    // wins
        } else {
            $result = 0;    // loses
        }

        $new_game = Game::create(['user_id' => $id,
                        'dice_1' => $dice_1,
                        'dice_2' => $dice_2,
                        'result' => $result,
        ]);

        return $this->sendResponse("New game created successfully.", $new_game, 201);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        // Validate if Auth user can act on the target id data
        if ( ! $this->userValidation($id) ){
            return $this->sendError("Not authorized to delete another user's games.", ['Auth User id : '.Auth::user()->id, 'Target User id : '.$id], 401); 
        }

        // Validate if target user exists
        $user = User::find($id);

        if(!$user) {
            return $this->sendError("Can't delete games for this user. User not found.", "Target User id = ".$id, 404);       
        }
        
        
        Game::destroy($user->games());

        return $this->sendResponse("Games deleted successfully.", "" , 200);
    }

    /**
     * Helper function: User validation
     * Check if Auth user can act on target user's data
     */
    public function userValidation($id)
    {
        // Get Auth user's role
        $auth_user_role = Auth::user()->role()->first()->role;

        // Do not allow to access another user's data if Auth user's role is 'player'
        if ( ($auth_user_role == 'player') && (Auth::user()->id != $id) ) {
            return false;
        }
        return true;
    }
}
