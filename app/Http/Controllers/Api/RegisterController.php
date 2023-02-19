<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Support\Str;
use App\Models\ReferralCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register (Request $request)
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
            'password' => bcrypt($password),
            'referral_code' => $referral,
        ]);

        UserWallet::create([
            'user_id' => $user->id,
            'market' => 'USD',
            'amount' => 0
        ]);

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
