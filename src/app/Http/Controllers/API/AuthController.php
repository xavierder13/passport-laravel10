<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $token = $user->createToken('API Token')->accessToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $rules = [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
        ];

        $valid_fields = [
            'email' => 'required',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $valid_fields, $rules);

        if($validator->fails())
        {   
            return response()->json($validator->errors(), 200);
        }

        if(!Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')], true))
        {   
            return response()->json(['error' => 'Invalid credentials'], 200);
        }

        $user = Auth::user();
        $user->last_login = Carbon::now()->toDateTimeString();
        $user->save();
        
        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        
        $user_roles = Auth::user()->roles->pluck('name')->all();

        $user_permissions = Auth::user()->getAllPermissions()->pluck('name');

        return response()->json([
            'user' => Auth::user(), 
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'user_permissions' => $user_permissions,
            'user_roles' => $user_roles,
        ], 200);
    }

    public function me(Request $request)
    {   
        $user = Auth::user();
        $user_roles = $user->roles->pluck('name')->all();
        $user_permissions = $user->getAllPermissions()->pluck('name');
    
        return response()->json([
            'user' => $request->user(),
            'user_roles' => $user_roles, 
            'user_permissions' => $user_permissions,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
    
}
