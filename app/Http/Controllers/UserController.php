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
                "data" => JWTAuth::user(),
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

    public function showalluser(Request $request)
    {
        try {
            $id = $request->query('id');

            if ($id !== null) {
                $user = User::find($id);

                if (!$user) {
                    return response()->json([
                        'message' => 'User not found'
                    ], 404);
                }

                return response()->json([
                    'message' => 'user retrieved successfully',
                    'data' => $user
                ], 200);
            }

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

    public function editUser(Request $request)
    {
        try {
            $id = $request->query('id');
            $request->validate([
                'first_name' => 'sometimes|required|string|max:50',
                'last_name' => 'sometimes|required|string|max:50',
                'email' => 'sometimes|required|email|max:255|unique:users,email,' . $id,
                'password' => 'sometimes|required|string|min:8',
            ]);

            
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            $data = $request->only('first_name', 'last_name', 'email', 'password');

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);

            return response()->json([
                'message' => 'User updated successfully',
                'data' => $user
            ], 200);
        } catch (Exception $e) {
            Log::error('User update error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
