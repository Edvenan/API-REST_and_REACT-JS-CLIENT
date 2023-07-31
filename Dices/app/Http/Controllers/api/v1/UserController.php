<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\api\v1\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

//use Illuminate\Foundation\Auth\User;

class UserController extends BaseController
{

    // We define in constructor which methods can be called by an unauthorized user.
    // In order to call any other method, the user must use a valid token
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'c_password' => 'same:password',
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 400);       
        }
     
        $request->name = $request->name == ''? 'Anonymous' : $request->name; 

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
   
        return $this->sendResponse('User register successfully.', $user->only(['name','email']), 201);
    
    }

    
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }


        /**
         * Another way: If the validation fails (e.g., the email is missing or not in a valid format), Laravel will automatically 
         * redirect back to the previous page with the validation errors, making it easy to display error messages to the user.
         */
        /* $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

         */

        $credentials = $request->only(['email', 'password']);
        
        if (auth()->attempt($credentials)) {
            $user = Auth::user();

            // create OAuth Access Token
            $data['name'] = $user->name;
            $data['token'] = $user->createToken($user->email."'s token")->accessToken;

            return $this->sendResponse('User logged in successfully.', $data, 201);
        }
        
        return $this->sendError('Invalid credentials.', 401);  
    }

    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::user()->tokens()->delete();
        /* return response()->json([
            'message' => 'Successfully logged out',
        ]); */
        return $this->sendResponse('User logged out successfully.', Auth::user()->only(['name', 'email']), 201);
    }

    /**
     * Dummy function to test its access with or w/o auth/api middleware
     */
    public function index()
    {
        return response(['message' => "Hola!"]);
    }
}
