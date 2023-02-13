<?php

namespace App\Classes;

use App\Models\UserWallet;
use App\Models\UserTransaction;

class WhitebitPrivate extends WhitebitPublic
{
    static function getTradingBalance()
    {
        $endpoint = '/api/v4/trade-account/balance';
        $nonce = (string) (int) (microtime(true) * 1000);

        $data = [
            'ticker' => 'BTC',
            'request' => $endpoint,
            'nonce' => $nonce,
            'nonceWindow' => true,
        ];

        $response = parent::makeRequest($endpoint, true, $data, 'POST');
        return json_decode($response, true);
    }

    static function getMainAddress($ticker)
    {
        $endpoint = '/api/v4/main-account/address';
        $nonce = (string) (int) (microtime(true) * 1000);

        $data = [
            'ticker' => $ticker,
            'request' => $endpoint,
            'nonce' => $nonce,
            'nonceWindow' => true,
        ];
        
        $response = parent::makeRequest($endpoint, true, $data, 'POST');
        return json_decode($response, true);
    }


    static function mainAccountHistory($request, $data)
    {
        $response = parent::makeRequest($request, true, $data, 'POST');
        $response = json_decode($response, true);
        $status = 0;
        
        try 
        {
            if (count($response['records']) > 0)
            {
                $status = $response['records'][0]['status'];
            }
        } 
        catch (\Throwable $err) 
        {
            //throw $th;
        }

        return [
            'data' => $response,
            'status' => $status,
        ];
    }


    static function updateMoneyWithCron() 
    {
        $transactions = UserTransaction::where('status', 0)->get();
        $nonce = (string) (int) (microtime(true) * 1000);
        $request = '/api/v4/main-account/history';

        echo $nonce;

        foreach($transactions as $transaction){
            echo 'Working on: ' . $transaction->uniqueId . '\n';

            $data = [
                'offset' => 0,
                'limit' => 10,
                'nonce' => $nonce,
                'uniqueId' => $transaction->uniqueId,
                'request' => $request,
            ];

            $assocResponse = self::mainAccountHistory($request, $data);
            $response = $assocResponse['response'];
            $status = $assocResponse['status'];

            var_dump($response);

            if ($status == 3 || $status == 7) 
            {
                $wallet = UserWallet::where([
                    ['user_id', $transaction->user_id],
                    ['market', $transaction->ticker]
                ]);

                if ($wallet->count() == 0) 
                {
                    $transaction->update(['status' => 1]);

                    UserWallet::create([
                        'user_id' => $transaction->user_id,
                        'market' => $transaction->ticker,
                        'amount' => $response['records'][0]['amount'],
                    ]);
                } 
                else 
                {
                    $transaction->update(['status' => 7]);

                    $amount =  UserWallet::where([
                        ['user_id', $transaction->user_id],
                        ['market', $transaction->ticker]
                    ])->first()->amount;

                    UserWallet::where([
                        ['user_id', $transaction->user_id],
                        ['market', $transaction->ticker]

                    ])->update([
                        'amount' => $amount + $response['records'][0]['amount'],
                    ]);
                }
            }
        }
    }
}