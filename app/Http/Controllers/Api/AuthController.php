<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Models\ReferralCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\kyc;
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
                'doc_front' => 'required|file|mimes:jpeg,jpg,png',
                'doc_type' => 'required|string|max:255'
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

        $valid_documents = ['id_card', 'passport'];

        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $referral = optional($request->referral_code)->get();
        $docType = $request->doc_type;
        $kyc = null;

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'referral_code' => $referral,
        ]);
        
        if (!in_array($docType, $valid_documents)) 
        {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification document type'
            ]);
        } 
        else 
        {
            $docFront = $request->file('doc_front');
            $docBack = $request->file('doc_back');

            if (!empty($docBack)) {
                $validator = Validator::make(
                    ['doc_back' => $docBack],
                    ['doc_back' => 'required|file|mimes:jpeg,jpg,png']
                );
                
                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid verification document'
                    ]);
                }
            }

            $kyc = kyc::create([
                'user_id' => $user->id,
                'doc_type' => $docType,
                'doc_front' => $docFront->store('kyc'),
                'doc_back' => $docBack ? $docBack->store('kyc') : null,
                'is_verified' => 0
            ]);
        }

        $user->generateWallets();

        ReferralCode::create([
            'code' => Str::random(18),
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully'
        ]);
    }
}
