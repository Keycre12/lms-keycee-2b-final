<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'u_name' => ['required', 'string', 'max:255'],
            'u_email' => ['required', 'email', 'max:255', 'unique:users,u_email'],
            'u_pass' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'same:u_pass'],
        ]);

        $user = User::create([
            'u_name' => $request->u_name,
            'u_email' => $request->u_email,
            'u_pass' => Hash::make($request->u_pass),
            'role_id' => 1,
            'status' => 'Active',
        ]);

        return response()->json(['message' => 'User created successfully!', 'user' => $user]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'u_email' => ['required', 'email'],
            'u_pass' => ['required', 'string'],
        ]);

        $user = User::where('u_email', $request->u_email)->first();

        if (!$user || !Hash::check($request->u_pass, $user->u_pass)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully!',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully!']);
    }
}
