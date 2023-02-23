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

    static function withdrawRequest(
        $ticker,
        $amount,
        $address,
        $type,
        $provider = 'VISAMASTER',
        $beneficiary = [],
        $network = ''
    )
    {
        $endpoint = '/api/v4/main-account/withdraw';
        $nonce = (string) (int) (microtime(true) * 1000);
        $data = [
            'ticker' => $ticker,
            'amount' => $amount,
            'address' => $address,
            'uniqueId' => time() . rand(1, 9999999),
            'request' => $endpoint,
            'nonce' => $nonce,
        ];

        if ($type === 'fiat')
        {
            $data['provider'] = $provider;
            $data['beneficiary'] = $beneficiary;
        }
        else 
        {
            $data['network'] = $network;
        }

        $response = parent::makeRequest($endpoint, true, $data, 'POST');

        return json_decode($response, true);
    }


    static function withdraw($ticker, $amount, $cardNumber, $beneficiarFirstname, $beneficiarLastname, $email, $phone, $provider='VISAMASTER') 
    {
        $endpoint = '/api/v4/main-account/withdraw';
        $nonce = (string) (int) (microtime(true) * 1000);
        $uniqueId = Helpers::generateUserUniqueID($email);

        $data = [
            'ticker' => $ticker,
            'amount' => (string) $amount,
            'address' => $cardNumber,

            'beneficiary' => [
                'firstName' => $beneficiarFirstname,
                'lastName' => $beneficiarLastname,
                'phone' => $phone,
            ],
            
            'provider' => $provider,
            'uniqueId' => $uniqueId,
            'request' => $endpoint,
            'nonce' => $nonce
        ];

        $response = parent::makeRequest($endpoint, true, $data, 'POST');
        return json_decode($response, true);
    }


    static function getHistory($offset=0, $limit=100, $uniqueId=null) 
    {
        $endpoint = '/api/v4/main-account/history';
        $nonce = (string) (int) (microtime(true) * 1000);

        $data = [
            'request' => $endpoint,
            'nonce' => $nonce,
            'offset' => $offset,
            'limit' => $limit
        ];

        if ($uniqueId !== null) {
            $data['uniqueId'] = $uniqueId;
        }

        $response = parent::makeRequest($endpoint, true, $data, 'POST');
        return json_decode($response, true);
    }

    static function getFiatDepositURI($ticker, $provider, $amount, $userFirstname, $userLastname, $userEmail, $successLink = null, $failureLink = null, $returnLink = null) 
    {   
        $endpoint = '/api/v4/main-account/fiat-deposit-url';
        $nonce = (string) (int) (microtime(true) * 1000);

        $data = [
            'ticker' => $ticker,
            'provider' => $provider,
            'amount' => $amount,
            'uniqueId' => time() . rand(1, 9999),
            'customer' => [
                'firstName' => $userFirstname,
                'lastName' => $userLastname,
                'email' => $userEmail,
            ],

            'request' => $endpoint,
            'nonce' => $nonce
        ];

        if ($successLink !== null) 
        {
            $data['successLink'] = $successLink;
        } 

        if ($failureLink !== null)
        {
            $data['failureLink'] = $failureLink;
        }

        if ($returnLink !== null)
        {
            $data['returnLink'] = $returnLink;
        }

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