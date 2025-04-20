<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->assignRole('user');
            $user['token'] = $user->createToken('library_token')->plainTextToken;

            return response()->json([
                'status' => 200,
                'data' => $user,
                'message' => 'User registered successfully.'
            ]);
        } catch (Exception $th) {
            return response()->json([
                'status' => 500,
                'data' => [],
                'message' => 'Something went wrong! Please try again later.'
            ]);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user['token'] = $user->createToken('library_token')->plainTextToken;

            return response()->json([
                'status' => 200,
                'data' => $user,
                'message' => "User login successfully",
            ]);
        } catch (ModelNotFoundException $th) {
            return response()->json([
                'status' => 400,
                'data' => [],
                'message' => "User not found.",
            ]);
        } catch (Exception $th) {
            return response()->json([
                'status' => 500,
                'data' => [],
                'message' => "Something went wrong! Please try again later.",
            ]);
        }
    }
}
