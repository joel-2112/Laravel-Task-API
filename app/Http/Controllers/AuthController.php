<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Step 1: Validate the input data
            $validatedData = $request->validate([
                'name' => 'required|string|max:200',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
    
            // Step 2: Hash the password -> laravel hash it by defult
            // $validatedData['password'] = Hash::make($validatedData['password']);
    
            // Step 3: Create the user
            $user = User::create($validatedData);
    
            // Step 4: Generate an authentication token
            $token = $user->createToken('auth_token')->plainTextToken;
    
            // Step 5: Return a JSON response
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ], 201);
    
        } catch (\Exception $e) {
            // Step 6: Handle any exceptions
            return response()->json([
                'message' => 'An error occurred during registration',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function login(Request $request)
    {
        try {
            // Step 1: Validate the input data
            $validatedData = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
    
            // Step 2: Attempt to authenticate the user
            if (!Auth::attempt($validatedData)) {
                return response()->json([
                    'message' => 'Invalid login details'
                ], 401);
            }
    
            // Step 3: Retrieve the authenticated user
            $user = User::where('email', $validatedData['email'])->firstOrFail();
    
            // Step 4: Generate an authentication token
            $token = $user->createToken('auth_token')->plainTextToken;
    
            // Step 5: Return a JSON response
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ], 200);
    
        } catch (\Exception $e) {
            // Step 6: Handle any exceptions
            return response()->json([
                'message' => 'An error occurred during login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Step 1: Check if the user is authenticated
            if (!$request->user()) {
                return response()->json([
                    'message' => 'User not authenticated'
                ], 401);
            }
    
            // Step 2: Revoke the token
            $request->user()->currentAccessToken()->delete();
    
            // Step 3: Return a success response
            return response()->json([
                'message' => 'Logged out successfully'
            ], 200);
    
        } catch (\Exception $e) {
            // Step 4: Handle any exceptions
            return response()->json([
                'message' => 'An error occurred during logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}