<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'lastname' => 'required|string',
            'role' => 'required|in:admin,user',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'gender' => 'required|in:male,female'
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'role' => $request->role
        ]);

        $token = $user->createToken('token_name')->plainTextToken;
        return response()->json(['token' => $token]);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        $token = $user->createToken('token_name')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function getUser(Request $request){
        return response()->json($request->user());
    }
}
