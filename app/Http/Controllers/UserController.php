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

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            return response()->json([
                'message' => 'Login successful',
                'token' => $token
            ], 200);
        } catch (Exception $e) {
            Log::error('Login error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'message' => 'Failed to login',
                'error' => $e->getMessage() ?: 'An error occurred'
            ], 500);
        }
    }

    public function showalluser()
    {
        try {
            $user = User::all();
            return response()->json([
                'message' => 'all user retrieved successfully',
                'data' => $user
            ], 200);
        } catch (Exception $e) {
            Log::error('User retrieval error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage() ?: 'An error occurred'
            ], 500);
        }
    }

    public function showuserbyid(Request $request)
    {
        try {
            $id = $request->id;
            $user = User::findOrFail($id);
            return response()->json([
                'message' => 'user retrieved successfully',
                'data' => $user
            ], 200);
        } catch (Exception $e) {
            Log::error('User retrieval error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage() ?: 'An error occurred'
            ], 500);
        }
    }
}
