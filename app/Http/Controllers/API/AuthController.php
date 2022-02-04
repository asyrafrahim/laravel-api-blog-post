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
        
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        
        if (auth()->attempt($data)) {
            $user = Auth::user();
            $token = auth()->user()->createToken('BlogApp')->accessToken;
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
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
            return $this->sendError('Validation error.', $validator->errors(), 422);       
        }
        
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        
        $token = $user->createToken('BlogApp')->accessToken;
        
        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'token' => $token,
            'user' => $user
        ], 200);
    }
    
    public function logout(Request $request)
    {
        if (Auth::user()) {
            $token = Auth::user()->token();
            $token->revoke();
            
            return response()->json([
                'success' => true,
                'message' => 'Logout successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to Logout'
            ]);
        }
    }
}