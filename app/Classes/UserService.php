<?php

namespace App\Classes;

use App\Models\Fee;
use App\Models\UserFee;
use App\Models\UserOrders;
use App\Models\UserWallet;
use App\Models\UserTransaction;

class UserService 
{
    static function createUserTransaction($user_id, $ticker, $method, $address, $nonce, $amount)
    {
        return UserTransaction::create([
            'user_id' => $user_id,
            'ticker' => $ticker,
            'method' => $method,
            'address' => $address,
            'nonce' => $nonce,
            'amount' => $amount,
            'uniqueId' => time() . rand(1, 9999),
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

    static function exchangeCurrency($market, $amount, $user_id, $type)
    {
        $assets = WhitebitPublic::getAssetkeys();
        $types = ['buy', 'sell'];

        if (!in_array($market, $assets, true))
        {
            return [
                'success' => false,
                'message' => 'Market not found'
            ];
        }

        $type = strtolower($type);
        if (!in_array($type, $types, true))
        {
            return [
                'success' => false,
                'message' => 'Invalid transaction type'
            ];
        }

        $market = explode('_', $market);

        $sell = $market[0];
        $buy = $market[1];

        $funds = UserWallet::where([
            'user_id' => $user_id,
            'market' => $sell,
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
            'amount' => $amount,
            'side' => 'buy',
        ]);

        $data = [
            'market' => $market,
            'side' => 'buy',
            'amount' => $amount,
            'price' => '0',
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

            $funds->amount = $funds->amount - $amount;
            $process = UserWallet::where(['user_id' => $user_id, 'market' => $buy])->first();

            if ($process)
            {
                $process->update([
                    'amount' => $process->amount + $responseData['dealStock']
                ]);
            }
        }

        return $responseData;
    }
}