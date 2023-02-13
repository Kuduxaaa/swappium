<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' =>'required|email|max:255',
            'password' =>'required|min:8',
        ]);

        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password]))
        {
            $user = Auth::user();
            $token = $user->createToken('SwappiumPrivateProfile')->accessToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user
            ]);
        }

        return response()->json([
           'success' => false,
           'message' => 'User email or password are incorrect'
        ]);
    }
}
