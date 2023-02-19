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


    /*
      I'm writing to request assistance with a fiat currency withdrawal via the WhiteBIT API. 
      Specifically, I'm unsure of what information to provide in the "address" field, 
      as the documentation states that this field is for "wallet address for cryptocurrencies, 
      identifier/card number for fiat currencies." As I'm looking to withdraw funds in fiat currency, 
      I'm not sure what to enter in the "address" field. Could you please provide some guidance on 
      what information to enter in this field for a fiat currency withdrawal?
    */

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


    static function withdrawWithIBAN($ticker, $amount, $iban, $beneficiarFirstname, $beneficiarLastname, $provider='VISAMASTER') 
    {
        $endpoint = '/api/v4/main-account/withdraw';
        $nonce = (string) (int) (microtime(true) * 1000);

        $data = [
            'ticker' => $ticker,
            'amount' => (string) $amount,
            'address' => $iban,

            'beneficiary' => [
                'firstName' => $beneficiarFirstname,
                'lastName' => $beneficiarLastname,
                'phone' => '+995574058565',
            ],
            
            'provider' => $provider,
            'uniqueId' => time() . rand(1, 9999),
            'request' => $endpoint,
            'nonce' => $nonce
        ];

        $response = parent::makeRequest($endpoint, true, $data, 'POST');
        return json_decode($response, true);
    }


    static function getHistory($offset=0, $limit=100) 
    {
        $endpoint = '/api/v4/main-account/history';
        $nonce = (string) (int) (microtime(true) * 1000);

        $data = [
            'request' => $endpoint,
            'nonce' => $nonce,
            'offset' => $offset,
            'limit' => $limit
        ];

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