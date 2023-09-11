<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\api\v1\BaseController as BaseController;
use App\Models\Role;
use App\Models\User;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


//use Illuminate\Foundation\Auth\User;

class UserController extends BaseController
{

    /**
     * Create Player
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // user input validation
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'c_password' => 'required|same:password',
        ]);
     
        if($validator->fails()){

            return $this->sendError('Bad request. Validation Error.',['error' => $validator->errors()], 400);       

        }

        // If no name is given, we set it as 'anonymous'
        $request->name = $request->name == ''? 'Anonymous' : $request->name; 

        // insert user in users table
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // insert role 'players' as default in roles table 
        Role::create([
            'user_id' => $user->id,
            'role' => 'player'
        ]);
   

        $data['user'] = $user;
        $data['role'] = 'player';

        return $this->sendResponse('User registered successfully.', $data, 201);

    
    }

    /**
     * User Login
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // user input validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', ['error' => $validator->errors()], 400);      
        }

        $credentials = $request->only(['email', 'password']);
        
        if (auth()->attempt($credentials)) {
            $user = Auth::user();
            
            // get user's role and set its scope
            $userRole = $user->role()->first()->role;
            
            if (!$userRole) {
                return $this->sendError('Not found. User with no role assigned.', 404); 
            }
            

            $this->scope = $userRole;

            // create OAuth Access Token, adding scope to it
            $data['user'] = $user;
            $data['role'] = $userRole;

            $data['token'] = $user->createToken($user->email."'s token", [$this->scope])->accessToken;

            return $this->sendResponse('User logged in successfully.', $data, 201);
        }
        
        return $this->sendError('Unauthorized. Invalid credentials.',[], 401);  
    }

    /**
     * User Logout
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        // delete entry (Token) in Oauth Access Tokens table
        Auth::user()->tokens()->delete();
    
        return $this->sendResponse('User logged out successfully.', ['user' => Auth::user()], 201);

    }

    /**
     * Edit User name function
     */
    public function edit($id, Request $request)
    {
        // Validate if Auth user can act on the target id data
        if ( ! $this->userValidation($id) ){
            return $this->sendError("Forbidden. Not authorized to modify another user's name.", ['Auth_User_Id' => Auth::user()->id, 'Target_User_Id' => $id], 403); 
        }

        $user = User::find($id);

        if(!$user) {
            return $this->sendError("Not found. Can't edit user name.", ["Target_User_Id" => $id], 404);       
        }

        // user input validation
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255|unique:users'
        ]);

        if($validator->fails()){
            // handle if validation fails because new name = current name, 
            if( $request->name == $user->name){

                return $this->sendError('Bad request. New user name matches current user name. User name has not been modified.', ['data' => $user], 400);
            }
            // handle if validation fails because new name = anonymous, 
            else if( $request->name == 'Anonymous'){
                // don't send report and continue
            }
            else return $this->sendError('Bad request. Validation Error.',['error' => $validator->errors()], 400);      
        }

        if($request->missing('name')){
            return $this->sendError('Bad request. No user name given. User name has not been modified.',['data' => $user], 400);

        }

        // If blank name is given, we set it as 'anonymous'
        $user->name = $request->name == ''? 'Anonymous' : $request->name; 
        $user->update();

        $data['user'] = $user;
        return $this->sendResponse('User name modified successfully.', $data, 201);

    }

    /**
     * List user games
     */
    public function listGames($id, Request $request)
    {
        
        // Validate if Auth user can act on the target id data
        if ( ! $this->userValidation($id) ){
            return $this->sendError("Forbidden. Not authorized to list another user's games.", ['Auth_User_Id' => Auth::user()->id, 'Target_User_Id' => $id], 403); 
        }

        $user = User::find($id);

        if(!$user) {
            return $this->sendError("Not found. Can't list this user's games.", ["Target_User_Id" => $id], 404);       
        }


        return $this->sendResponse("List of user's games retrieved successfully.", ['Auth_User_Id' => Auth::user()->id, 'Target_User_Id' => $user->id, 'Target_User' => $user, 'WinsRate' => $user->winsRate()], 200);

    }

    /**
     * List players
     */
    public function listPlayers()
    {
        $players = $this->getAllPlayers();

        // calculate all players' average wins rate
        $avg_winsRate = empty($players)? 0 : array_sum(array_column($players,'winsRate')) / count($players);

        return $this->sendResponse("List of players retrieved successfully.", ['players' => $players, 'avg_winsRate' => $avg_winsRate], 200);
    }

    /**
     * Ranking
     */
    public function ranking()
    {
        $players = $this->getAllPlayers();
        $ranking = $this->addRank($players);

        return $this->sendResponse("Ranking retrieved successfully.",['ranking'=>$ranking], 200);
    }

    /**
     * Loser
     */
    public function loser()
    {
        $players = $this->getAllPlayers();
        $ranking = $this->addRank($players);

        $lowest = collect($ranking)->max('rank');

        $loser = collect($ranking)->where('rank', $lowest)->values()->toArray();
        $loser = empty($loser)? [] : $loser;

        return $this->sendResponse("Loser retrieved successfully.", ['loser'=>$loser], 200);
    }

    /**
     * Winner
     */
    public function winner()
    {
        $players = $this->getAllPlayers();
        $ranking = $this->addRank($players);

        $highest_rank = collect($ranking)->min('rank');

        $winner = collect($ranking)->where('rank', $highest_rank)->values()->toArray();
        $winner = empty($winner)? [] : $winner;


        return $this->sendResponse("Winner retrieved successfully.", ['winner'=>$winner], 200);
    }

    /**
     * Delete User
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if(!$user) {
            return $this->sendError("Not found. User could not be deleted.", ["User_Id" => $id], 404);       
        }

        if (Auth::user()->id == $id){
            return $this->sendError("Forbidden. User cannot delete itself.", ["User_Id" => $id], 403);    
        }
        
        $user->delete();
        

        return $this->sendResponse("User deleted successfully.", [], 200);

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

    /**
     * Helper function: Get all Players from Users and their respective wins rate
     */
    public function getAllPlayers()
    {
        $players = [];

        // get all users
        $users = User::all();
        
        // get all players from users and their respective wins rates
        foreach ($users as $user){
            if ($user->role()->first()->role == 'player'){
                $players[] = $user->attributesToArray() + array('winsRate' => $user->winsRate());
            }
        }
        return $players;
    }

    /**
     * Helper function: sort Players list by wins rate in Desc order
     */
    public function sortPlayersList($players)
    {
        // Sort players array by wins rate
        $players_winsRate = array_column($players,'winsRate');
        array_multisort($players_winsRate,SORT_DESC, $players);

        return $players;
    }

    /**
     * Helper function: takes sorted player list as input and returns Players list with new key:value pair for rank
     */
    public function addRank($players){
        // Get the top-scoring player's score (the winner's score) or return [] if not found
        try {
            $sorted_players = $this->sortPlayersList($players);
            $topScore = collect($sorted_players)->max('winsRate');
        } catch (ErrorException $e) {
            return [];
        }
        
        // add rank to each player's data
        $prev_winsRate = $topScore;
        $rank = 1;
        $ranked_players =[];

        foreach ($sorted_players as $player){
            $rank = $player['winsRate'] < $prev_winsRate? $rank+1 : $rank;
            $prev_winsRate = $player['winsRate'];
            $ranked_players[] = $player + array('rank' => $rank);
        }

        return $ranked_players;
    }
}
