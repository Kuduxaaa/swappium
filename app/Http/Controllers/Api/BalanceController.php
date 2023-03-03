<?php

namespace App\Http\Controllers\Api;

use App\Classes\Helpers;
use App\Models\UserFee;
use App\Models\UserWallet;
use App\Classes\UserService;
use Illuminate\Http\Request;
use App\Classes\WhitebitPrivate;
use App\Http\Controllers\Controller;

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
            ['ticker', 'USD']
        ])->first();

        return response()->json([
            'success' => true,
            'amount' => $wallet->amount,
        ]);
    }
    

    public function deposit (Request $request) 
    {
        $request->validate([
            'ticker' => 'required',
            'amount' => 'required',
            'provider' => 'required',
        ]);

        $ticker = $request->get('ticker');
        $amount = $request->get('amount');
        $provider = $request->get('provider');
        $user = $request->user();

        if ($user->role !== 2)
        {    
            $userFee = UserFee::where('user_id', $user->id)->first();
            $fee = ($userFee) ? $userFee->fee : 1;

            $amount = Helpers::calculatePercentage($amount, $fee);
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        if ($amount < 10 || $amount > 1500) {
            return response()->json([
                'success' => false,
                'message' => 'Amount must be between 10 and 1500'
            ]);
        }
        
        $userNameSplitted = explode(' ', $user->name);
        $userFirstName = $userNameSplitted[0];
        $userLastName = $userNameSplitted[1];
        $email = $user->email;

        $link = WhitebitPrivate::getFiatDepositURI(
            $ticker, 
            $provider, 
            $amount, 
            $userFirstName, 
            $userLastName, 
            $email
        );

        $nonce = (string) (int) (microtime(true) * 1000);
        UserService::createUserTransaction($user->id, $ticker, 'deposit', null, $nonce, $amount);

        return $link;
    }

    public function withdraw(Request $request) 
    {

        $request->validate([
            'ticker' => 'required',
            'amount' => 'required',
            'card_number' => 'required',
            'phone' => 'required',
        ]);

        $ticker = $request->get('ticker');
        $amount = $request->get('amount');
        $card_number = $request->get('card_number');
        $user = $request->user();
        $phone = $request->get('phone');

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        if ($amount < 10 || $amount > 1500) {
            return response()->json([
                'success' => false,
                'message' => 'Amount must be between 10 and 1500'
            ]);
        }
        
        $userNameSplitted = explode(' ', $user->name);
        $userFirstName = $userNameSplitted[0];
        $userLastName = $userNameSplitted[1];
        $email = $user->email;

        $responese = WhitebitPrivate::withdraw($ticker, $amount, $card_number, $userFirstName, $userLastName, $email, $phone);
        
        UserService::createUserTransaction(
            $user->id,
            $ticker, 
            'withdraw', 
            $card_number, 
            (string) (int) (microtime(true) * 1000), 
            $amount
        );

        return response()->json([
            'success' => (count($responese) == 0)
        ]);
    }

    public function history(Request $request)
    {
        $user = $request->user();

        if (!$user)
        {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }
        
        $uniqueId = Helpers::generateIdentifier($user->email);

        return WhitebitPrivate::getHistory(0, 100, $uniqueId);
    }

    public function exchange(Request $request)
    {
        $request->validate([
            'market' => 'required',
            'amount' => 'required',
            'side' => 'required',
            'price' => 'required',
        ]);

        $user = $request->user();
        $market = $request->get('market');
        $amount = $request->get('amount');
        $side = $request->get('side');
        $price = $request->get('price');
        $user_id = $user->id;

        return UserService::createLimitOrder($market, $amount, $price, $side, $user_id);
    }

    public function quickExchange(Request $request)
    {
        $request->validate([
            'market' => 'required',
            'amount' => 'required',
            'side' => 'required',
        ]);

        $user = $request->user();
        $market = $request->get('market');
        $amount = $request->get('amount');
        $ra = $request->get('ra');
        $side = $request->get('side');
        $user_id = $user->id;

        return UserService::createMarketOrder($market, $amount, $ra, $side, $user_id);
    }
}
