<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class RegisterController extends Controller
{
    public function register(Request $req)
    {
        try {
            $user = User::create([
                'name' => $req->name,
                'email' => $req->email,
                'password' => Hash::make($req->password),
            ]);

            Log::info("User created: " . $user->email);

            // Dispatch the event
            event(new UserRegistered($user));
            Log::info("UserRegistered event dispatched for: " . $user->email);

            // Return response
            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            Log::error("Error in registration: " . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function editProfile(Request $request)
    {
        if (Gate::allows('edit-profile', $request->user())) {
            return response()->json([
                'message' => 'Profile updated successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }
    }
}
