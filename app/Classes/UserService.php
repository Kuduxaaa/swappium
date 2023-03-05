<?php

namespace App\Classes;

use App\Models\Fee;
use App\Models\UserFee;
use App\Models\UserOrders;
use App\Models\UserWallet;
use App\Models\UserTransaction;

class UserService 
{
    static function createUserTransaction($user_id, $ticker, $method, $address, $nonce, $amount, $uid)
    {
        return UserTransaction::create([
            'user_id' => $user_id,
            'ticker' => $ticker,
            'method' => $method,
            'address' => $address,
            'nonce' => $nonce,
            'amount' => $amount,
            'uniqueId' => $uid,
            'status' => 'pending',
        ]);
    }

    static function calcuateUserFee($userId)
    {
        $userFee = UserFee::where('user_id', $userId)->first();
        
        if ($userFee) 
        {
            return $userFee->fee;
        } 
        else 
        {
            return Fee::first()->fee;
        }
    }

    static function createLimitOrder($market, $amount, $price, $side, $user_id)
    {
        $market_splitted = explode('_', $market);
        $sell = ($side == 'buy') ? $market_splitted[1] : $market_splitted[0]; // Buy = USDT (გაცემა); Sell = DOGE (გაცემა);
        $sell = strtoupper($sell);
        $amount = strval($amount);

        $bal = WhitebitPrivate::getBalance($sell)['main_balance'];

        if ($bal > 0) 
        {
            WhitebitPrivate::transferMoney('main', 'spot', $sell, $bal);
        }

        $funds = UserWallet::where([
            'user_id' => $user_id,
            'ticker' => $sell,
        ])->first();


        if (!$funds || $funds && $funds->amount < ($price * $amount))
        {
            return [
                'success' => false,
                'message' => 'You dont have enough funds'
            ];
        }

        $endpoint = '/api/v4/order/new';
        $nonce = (string) (int) (microtime(true) * 1000);
        $orderId = time() . rand(1, 9999999);

        UserOrders::create([
            'order_id' => $orderId,
            'user_id' => $user_id,
            'market' => $market,
            'amount' => $amount,
            'side' => $side,
        ]);

        $data = [
            'market' => $market,
            'side' => $side,
            'amount' => $amount,
            'price' => $price,
            'clientOrderId'=> $orderId,
            'request' => $endpoint,
            'nonce' => $nonce,
        ];


        $response = WhitebitPublic::makeRequest($endpoint, true, $data, 'POST');
        $responseData = json_decode($response, true);

        return $responseData;
        
        if (array_key_exists('errors', $responseData)) 
        {
            UserOrders::where('order_id', $orderId)->update(['status' => 'declined']);
            return $responseData;
        }
        else
        {
            UserOrders::where('order_id', $orderId)->update([
                'taker_fee' => $responseData['takerFee'],
                'deal_stock' => $responseData['dealStock'],
                'status' => 'success',
            ]);

            $funds->update([
                'amount' => $funds->amount - ($price * $amount),
            ]);

            $process = UserWallet::where('user_id', $user_id)
                                  ->where('ticker', ($side == 'sell') ? $market_splitted[1] : $market_splitted[0])->first();

            if ($process)
            {
                $process->update([
                    'amount' => $process->amount + $responseData['dealStock']
                ]);
            }
        }

        return $responseData;
    }

    static function createMarketOrder($market, $amount, $ra, $side, $user_id)
    {
        $market_splitted = explode('_', $market);
        $sell = ($side == 'buy') ? $market_splitted[1] : $market_splitted[0]; // Buy = USDT (გაცემა); Sell = DOGE (გაცემა);
        $sell = strtoupper($sell);
        $amount_str = strval($amount);

        $bal = WhitebitPrivate::getBalance($sell)['main_balance'];

        if ($bal > 0) 
        {
            WhitebitPrivate::transferMoney('main', 'spot', $sell, $bal);
        }

        $funds = UserWallet::where([
            'user_id' => $user_id,
            'ticker' => $sell,
        ])->first();


        if (!$funds || $funds && $funds->amount < $amount)
        {
            return [
                'success' => false,
                'message' => 'You dont have enough funds'
            ];
        }

        $endpoint = '/api/v4/order/market';
        $nonce = (string) (int) (microtime(true) * 1000);
        $orderId = time() . rand(1, 9999999);

        UserOrders::create([
            'order_id' => $orderId,
            'user_id' => $user_id,
            'market' => $market,
            'amount' => $amount_str,
            'side' => $side,
        ]);

        $data = [
            'market' => $market,
            'side' => $side,
            'amount' => $amount_str,
            'clientOrderId'=> $orderId,
            'request' => $endpoint,
            'nonce' => $nonce,
        ];

        $response = WhitebitPublic::makeRequest($endpoint, true, $data, 'POST');
        $responseData = json_decode($response, true);

        if (array_key_exists('errors', $responseData)) 
        {
            UserOrders::where('order_id', $orderId)->update(['status' => 'declined']);
            return $responseData;
        }
        else
        {
            UserOrders::where('order_id', $orderId)->update([
                'taker_fee' => $responseData['takerFee'],
                'deal_stock' => $responseData['dealStock'],
                'status' => 'success',
            ]);

            $funds->update([
                'amount' => $funds->amount - $amount,
            ]);

            $tckr = ($side == 'sell') ? $market_splitted[1] : $market_splitted[0];
            $ext_key = 'dealStock';

            if (in_array($tckr, ['USDT', 'USDC', 'DAI', 'TUSD', 'DECL', 'EURT']))
            {
                $ext_key = 'dealMoney';
            }

            $process = UserWallet::where('user_id', $user_id)
                                  ->where('ticker', $tckr)
                                  ->first();

            if ($process)
            {
                $process->update([
                    'amount' => floatval($process->amount) + floatval($responseData[$ext_key])
                ]);
            }
        }

        return $responseData;
    }
}