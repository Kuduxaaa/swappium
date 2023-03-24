<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Models\ReferralCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
        ]);

        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            $token = $user->createToken('SwappiumPrivateProfile')->accessToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User email or password are incorrect',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully',
        ]);
    }

    public function register (Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' =>'required|string|min:8|confirmed',
            ]
        );

        if ($validator->fails())
        {
            $response = [
                'success' => false,
                'errors' => $validator->errors()
            ];

            return response()->json($response, 400);
        }

        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $referral = $request->referral_code ?? null;

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'referral_code' => $referral,
        ]);

        $user->generateWallets();

        ReferralCode::create([
            'code' => Str::random(18),
            'user_id' => $user->id
        ]);

        $token = $user->createToken('SwappiumPrivateProfile')->accessToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'message' => 'User registered successfully'
        ]);
    }
}
