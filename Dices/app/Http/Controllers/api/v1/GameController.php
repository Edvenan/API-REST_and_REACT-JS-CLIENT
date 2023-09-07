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
     * Create new Game
     */
    public function create($id)
    {
        // Validate if Auth user can act on the target id data
        if ( ! $this->userValidation($id) ){
            return $this->sendError("Forbidden. Not authorized to create another user's games.", ['Auth_User_Id' => Auth::user()->id, 'Target_User_Id' => $id], 403); 
        }

        // Validate if target user exists
        $user = User::find($id);

        if(!$user) {
            return $this->sendError("Not found. Can't create a game for this user. User not found.", ["Target_User_Id" => $id], 404);       
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

        return $this->sendResponse("New game created successfully.",['Game' => $new_game, 'WinsRate' => $user->winsRate()], 201);

    }

    /**
     * Delete a user's games
     */
    public function destroy($id)
    {
        
        // Validate if Auth user can act on the target id data
        if ( ! $this->userValidation($id) ){
            return $this->sendError("Forbidden. Not authorized to delete another user's games.", ['Auth_User_Id' => Auth::user()->id, 'Target_User_Id' => $id], 403); 
        }

        // Validate if target user exists
        $user = User::find($id);

        if(!$user) {
            return $this->sendError("Not found. Can't delete games for this user. User not found.", ["Target_User_Id" => $id], 404);       
        }
                
        Game::destroy($user->games);

        return $this->sendResponse("Games deleted successfully.", [] , 200);
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
