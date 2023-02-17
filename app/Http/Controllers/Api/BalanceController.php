<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserWallet;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function getBalance(Request $request)
    {
        if (!$request->user())
        {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $wallet = UserWallet::where([
            ['user_id', $request->user()->id],
            ['market', 'USD']
        ])->first();

        return response()->json([
            'success' => true,
            'amount' => $wallet->amount,
        ]);
    }
}
