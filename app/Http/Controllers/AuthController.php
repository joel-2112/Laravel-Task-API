<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Step 1: Validate the input data
            $validatedData = $request->validate([
                'name' => 'required|string|max:200',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
            // Step 2: Create the user with the validated data
            $user = User::create($validatedData);
            // Step 3: Generate an authentication token
            $token = $user->createToken('auth_token')->plainTextToken;
            // Step 4: Return a JSON response
            return response()->json([
                'message'=>'registered successfully',
                'access_token' => $token,
                'user' =>$user              
            ], 201);
    
        } catch (\Exception $e) {
            // Step 5: Handle any exceptions
            return response()->json([
                'message' => $e->getMessage(),
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
                    'message' => 'Invalid login credentials'
                ], 401);
            }
            // Step 3: Retrieve the authenticated user
            $user = User::where('email', $validatedData['email'])->firstOrFail();
    
            // Step 4: Generate an authentication token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Step 5: Return a JSON response
            return response()->json([
                'message' => 'Logged in successfully',
                'access_token' => $token,
                'user' => $user
            ], 200);
    
        } catch (\Exception $e) {
            // Step 6: Handle any exceptions
            return response()->json([
                'message' => $e->getMessage(),
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
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            // Step 1: Check if the user is authenticated
            if (!$request->user()) {
                return response()->json([
                    'message' => 'User not authenticated'
                ], 401);
            }
            // Step 2: Generate a new token
            $token = $request->user()->createToken('auth_token')->plainTextToken;
            // Step 3: Return a JSON response
            return response()->json([
                'message' => 'Token refreshed successfully',
                'access_token' => $token
            ], 200);
    
        } catch (\Exception $e) {
            // Step 4: Handle any exceptions
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function Profile(Request $request)
    {
        try {
            // Step 1: Check if the user is authenticated
            if (!$request->user()) {
                return response()->json([
                    'message' => 'User not authenticated'
                ], 401);
            }
            // Step 2: Return a JSON response
            return response()->json([
                'user' => $request->user()
            ], 200);
    
        } catch (\Exception $e) {
            // Step 3: Handle any exceptions
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            // Step 1: Check if the user is authenticated
            if (!$request->user()) {
                return response()->json([
                    'message' => 'User not authenticated'
                ], 401);
            }
            // Step 2: Validate the input data
            $validatedData = $request->validate([
                'name' => 'required|string|max:200',
                'email' => 'required|string|email|max:200|unique:users,email,'.$request->user()->id,
            ]);
            // Step 3: Update the user
            $user = $request->user();
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->save();
            // Step 4: Return a JSON response
            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user
            ], 200);
    
        } catch (\Exception $e) {
            // Step 5: Handle any exceptions
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            // Step 1: Check if the user is authenticated
            if (!$request->user()) {
                return response()->json([
                    'message' => 'User not authenticated'
                ], 401);
            }
            // Step 2: Validate the input data
            $validatedData = $request->validate([
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);
            // Step 3: Check if the current password is correct
            if (!Hash::check($validatedData['current_password'], $request->user()->password)) {
                return response()->json([
                    'message' => 'Invalid current password'
                ], 401);
            }
            // Step 4: Update the password
            $user = $request->user();
            $user->password = Hash::make($validatedData['password']);
            $user->save();
            // Step 5: Return a JSON response
            return response()->json([
                'message' => 'Password updated successfully',
                'user' => $user
            ], 200);
    
        } catch (\Exception $e) {
            // Step 6: Handle any exceptions
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteProfile(Request $request)
    {
        try {
            // Step 1: Check if the user is authenticated
            if (!$request->user()) {
                return response()->json([
                    'message' => 'Please login to delete your profile'
                ], 401);
            }
            // Step 2: Delete the user
            $request->user()->delete();
            // Step 3: Return a success response
            return response()->json([
                'message' => 'Profile deleted successfully'
            ], 200);
    
        } catch (\Exception $e) {
            // Step 4: Handle any exceptions
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }


}