<?php

namespace App\Http\Controllers\Api;

use App\Classes\WhitebitPublic;
use App\Http\Controllers\Controller;
use App\Models\UserWallet;
use Illuminate\Http\Request;

class WalletsController extends Controller
{
    public function myWallets($type, Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        return response()->json(
            $user->getWallets($type)
        );
    }

    public function getBalance(Request $request)
    {
        $request->validate(['ticker' => 'required']);
        $ticker = $request->input('ticker');
        $user = $request->user();

        $wallet = UserWallet::where('user_id', $user->id)->where('ticker', $ticker)->first();

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => sprintf('Wallet for %s not found', $ticker)
            ]);
        }

        return response()->json([
            'success' => true,
            'amount' => $wallet->amount
        ]);
    }

    public function addWallet(Request $request)
    {
        $request->validate(['ticker' => 'required']);

        $assets = WhitebitPublic::getAssetkeys();
        $ticker = $request->input('ticker');
        $user = $request->user();

        $user_wallets = $user->getWallets();
        $uws = [];
        
        foreach ($user_wallets as $uw) {
            $uws[] = $uw->ticker;
        }

        if (in_array($ticker, $assets))
        {
            if (!in_array($ticker, $uws)) 
            {
                if ($user->generateWallets([$ticker]))
                {
                    return response()->json([
                        'success' => true,
                        'message' => sprintf('Wallet for %s successfully added', $ticker)
                    ]);
                }
            }
            else
            {
                return response()->json([
                    'success' => false,
                    'message' => 'This wallet is already you have'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong'
        ]);
    }
}
