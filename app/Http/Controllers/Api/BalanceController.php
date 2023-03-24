<?php

namespace App\Http\Controllers\Api;

use App\Classes\Helpers;
use App\Models\UserFee;
use App\Models\UserWallet;
use App\Classes\UserService;
use Illuminate\Http\Request;
use App\Classes\WhitebitPrivate;
use App\Http\Controllers\Controller;
use App\Models\UserTransaction;

class BalanceController extends Controller
{
    public function getFiatBalance(Request $request)
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
        $uid = time() . rand(1, 9999);

        $link = WhitebitPrivate::getFiatDepositURI(
            $ticker, 
            $provider, 
            $amount, 
            $userFirstName, 
            $userLastName, 
            $email,
            $uid,
        );

        $nonce = (string) (int) (microtime(true) * 1000);
        UserService::createUserTransaction($user->id, $ticker, 'deposit', null, $nonce, $amount, $uid);

        return $link;
    }

    public function withdraw(Request $request) 
    {

        $request->validate([
            'ticker' => 'required',
            'amount' => 'required',
            'card_number' => 'required|min:16|max:16',
            'phone' => 'required',
            'provider' => 'required',
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

        $wallet = UserWallet::where('user_id', $user->id)->where('ticker', strtoupper($ticker))->first();

        if (!$wallet) {
            return response()->json([
               'success' => false,
               'message' => 'Wallet not found'
            ], 404);
        }

        if ($wallet->amount < $amount) {
            return response()->json([
               'success' => false,
               'message' => 'Insufficient balance'
            ], 422);
        }

        $userNameSplitted = explode(' ', $user->name);
        $userFirstName = $userNameSplitted[0];
        $userLastName = $userNameSplitted[1];
        $uid = time() . rand(1, 9999);

        UserService::createUserTransaction(
            $user->id,
            $ticker, 
            'withdraw', 
            $card_number, 
            (string) (int) (microtime(true) * 1000), 
            $amount,
            $uid
        );

        $responese = WhitebitPrivate::withdraw($ticker, $amount, $card_number, $userFirstName, $userLastName, $uid, $phone);

        if ((count($responese) == 0))  
        {
            $wallet->update([
                'amount' => $wallet->amount - $amount
            ]);

            return response()->json([
               'success' => true,
               'message' => 'Successfully withdrawn, transaction working...'
            ]);
        }

        return $responese;
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
        
        $transactions = UserTransaction::where('user_id', $user->id)->get();

        return $transactions;
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

    public function withdrawCrypto(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'address' => 'required',
            'network' => 'required',
            'ticker' => 'required',
        ]);

        $user = $request->user();
        $amount = $request->get('amount');
        $address = $request->get('address');
        $network = $request->get('network');
        $ticker = $request->get('ticker');
        $user_id = $user->id;

        $wallet = UserWallet::where('user_id', $user_id)->where('ticker', $ticker)->first();

        if (!$wallet) {
            return response()->json([
               'success' => false,
               'message' => 'Wallet not found'
            ], 404);
        }

        if ($wallet->amount < $amount) {
            return response()->json([
              'success' => false,
              'message' => 'Insufficient balance'
            ], 422);
        }

        $uid = time() . rand(1, 9999);

        UserService::createUserTransaction(
            $user_id,
            $ticker, 
            'withdraw', 
            $address, 
            (string) (int) (microtime(true) * 1000), 
            $amount,
            $uid
        );

        $response = WhitebitPrivate::withdrawCrypto($ticker, $amount, $address, $network, $uid);

        if ((count($response) == 0))  
        {
            $wallet->update([
                'amount' => $wallet->amount - $amount
            ]);

            return response()->json([
              'success' => true,
              'message' => 'Successfully withdrawn, transaction working...'
            ]);
        }

        return $response;
    }
}
