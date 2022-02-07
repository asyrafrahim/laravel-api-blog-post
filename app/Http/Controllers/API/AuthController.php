<?php

namespace App\Http\Controllers\API;

use Auth;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
           
            'email' => 'required|email',
            'password' => 'required',

        ]);
   
        if ($validator->fails()) {
            return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                ], 401);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('BlogApp')-> accessToken;
            $success['name'] =  $user->name;
   
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token' => $success,
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ], 401);
        } 
    }
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
   
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 401);     
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('BlogApp')->accessToken;
        // $success['name'] =  $user->name;
   
        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'token' => $success,
            'user' => $user
        ], 200);
    }
    
    public function logout(Request $request)
    {
        if (Auth::user()) {
            $user = Auth::user()->token();
            $user->revoke();
    
            return response()->json([
                    'success' => true,
                    'message' => 'Logout successful'
                ]);
        } else {
            return response()->json([
                    'success' => false,
                    'message' => 'Unable to Logout'
                ]);
        }
    }
}