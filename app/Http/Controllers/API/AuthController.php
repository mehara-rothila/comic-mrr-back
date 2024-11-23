<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'User registered successfully',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            // Delete existing tokens for this user
            $user->tokens()->delete();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Successfully logged in',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Login failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Log debug information
            Log::info('Logout attempt', [
                'user_id' => $request->user()->id ?? 'no user',
                'token' => $request->bearerToken() ?? 'no token'
            ]);

            // Check if we have a user and token
            if (!$request->user() || !$request->bearerToken()) {
                Log::warning('Logout attempted without valid user or token');
                return response()->json([
                    'message' => 'No active session found'
                ], 401);
            }

            // Delete the current access token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Clear the token anyway
            if ($request->user()) {
                try {
                    $request->user()->tokens()->delete();
                } catch (\Exception $tokenError) {
                    Log::error('Error deleting tokens: ' . $tokenError->getMessage());
                }
            }

            return response()->json([
                'message' => 'Logout completed with errors',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function user(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }

            return response()->json([
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('User fetch error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching user data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}