<?php

namespace App\Http\Controllers;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Exception;

class UserController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|string|in:user,admin',
            ]);

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'User created successfully',
                'data' => $user,
                'token' => $token
            ], 201);
        } catch (Exception $e) {
            Log::error('User creation error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'message' => 'Failed to create user',
                'error' => $e->getMessage() ?: 'An error occurred'
            ], 500);
        }
    }
}
