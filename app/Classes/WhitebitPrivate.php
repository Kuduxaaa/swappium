<?php

namespace App\Classes;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\UserTransaction;

class WhitebitPrivate extends WhitebitPublic
{
    static function getBalance($ticker)
    {
        $endpoint = '/api/v4/main-account/balance';
        $nonce = (string) (int) (microtime(true) * 1000);

        $data = [
            'ticker' => $ticker,
            'request' => $endpoint,
            'nonce' => $nonce,
        ];

        $response = parent::makeRequest($endpoint, true, $data, 'POST');
        return json_decode($response, true);
    }


    static function withdrawCrypto($ticker, $amount, $address, $network, $uid) 
    {
        $bal = self::getBalance($ticker)['main_balance'];

        if ($bal > 0) 
        {
            self::transferMoney('main', 'spot', $ticker, $bal);
        }

        $endpoint = '/api/v4/main-account/withdraw';
        $nonce = (string) (int) (microtime(true) * 1000);

        $data = [
            'ticker' => $ticker,
            'amount' => $amount,
            'address' => $address,
            'uniqueId' => $uid,
            'network' => $network,
            'request' => $endpoint,
            'nonce' => $nonce,
        ];
        

        $response = parent::makeRequest($endpoint, true, $data, 'POST');
        return json_decode($response, true);
    }


    static function transferMoney($from, $to, $ticker, $amount)
    {
        $endpoint = '/api/v4/main-account/transfer';
        $nonce = (string) (int) (microtime(true) * 1000);

        $data = [
            'ticker' => $ticker,
            'amount' => $amount,
            'from' => $from,
            'to' => $to,
            'request' => $endpoint,
            'nonce' => $nonce
        ];
        

        $response = parent::makeRequest($endpoint, true, $data, 'POST');
        return json_decode($response, true);
    }


    static function withdraw($ticker, $amount, $cardNumber, $beneficiarFirstname, $beneficiarLastname, $uid, $phone, $provider='VISAMASTER') 
    {
        $bal = self::getBalance($ticker)['main_balance'];

        if ($bal > 0) 
        {
            self::transferMoney('spot', 'main', $ticker, $amount);
        }

        $endpoint = '/api/v4/main-account/withdraw';
        $nonce = (string) (int) (microtime(true) * 1000);

        if ($amount < 10 || $amount > 1500) {
            return response()->json([
                'success' => false,
                'message' => 'Amount must be between 10 and 1500'
            ]);
        }

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
            'uniqueId' => $uid,
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

    static function getFiatDepositURI($ticker, $provider, $amount, $userFirstname, $userLastname, $userEmail, $uid, $successLink = null, $failureLink = null, $returnLink = null) 
    {
        $bal = self::getBalance($ticker)['main_balance'];

        if ($bal > 0) 
        {
            self::transferMoney('main', 'spot', $ticker, $bal);
        }

        $endpoint = '/api/v4/main-account/fiat-deposit-url';
        $nonce = (string) (int) (microtime(true) * 1000);

        $data = [
            'ticker' => $ticker,
            'provider' => $provider,
            'amount' => $amount,
            'uniqueId' => $uid,
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

    static function getMainAddress($ticker, $network=null)
    {
        $endpoint = '/api/v4/main-account/address';
        $nonce = (string) (int) (microtime(true) * 1000);

        $data = [
            'ticker' => $ticker,
            'request' => $endpoint,
            'nonce' => $nonce,
        ];

        if ($network) 
        {
            $data['network'] = $network;
        }
        
        $response = parent::makeRequest($endpoint, true, $data, 'POST');
        return json_decode($response, true);
    }

    static function createNewAddress($ticker, $network = null) 
    {
        $endpoint = '/api/v4/main-account/create-new-address';
        $nonce = (string) (int) (microtime(true) * 1000);

        $data = [
            'ticker' => $ticker,
            'request' => $endpoint,
            'nonce' => $nonce,
        ];

        if ($network) 
        {
            $data['network'] = $network;
        }
        
        $response = parent::makeRequest($endpoint, true, $data, 'POST');
        return json_decode($response, true);
    }


    static function updateMoneyWithCron() 
    {
        $transactions = UserTransaction::where('status', 'pending')->get();

        if (count($transactions) > 0)
        {
            foreach ($transactions as $transaction)
            {
                $records = self::getHistory(0, 1, $transaction->uniqueId)['records'];

                if (count($records) > 0)
                {
                    $status = Helpers::getStatus($records[0]['status']);

                    if ($status === 'Success') 
                    {
                        $wallet = UserWallet::where('user_id', $transaction->user_id)->where('ticker', $transaction->ticker)->first();

                        if ($wallet) 
                        {
                            if ($records[0]['method'] == 1)
                            {
                                $wallet->amount = $wallet->amount + $transaction->amount;
                                $wallet->save();
                            }
                            else
                            {
                                $wallet->amount = $wallet->amount - $transaction->amount;
                                $wallet->save();
                            }
                        }
                    }

                    $transaction->update([
                        'status' => $status,
                    ]);
                }
            }
        }









        // $nonce = (string) (int) (microtime(true) * 1000);
        // $request = '/api/v4/main-account/history';

        // $data = [
        //     'offset' => 0,
        //     'limit' => 10,
        //     'nonce' => $nonce,
        //     'request' => $request,
        // ];

        // $response = self::mainAccountHistory($request, $data);

        // print_r($response);

        // echo $nonce;

        // foreach($transactions as $transaction)
        // {
        //     echo 'Working on: ' . $transaction->uniqueId . '\n';

        //     $data = [
        //         'offset' => 0,
        //         'limit' => 10,
        //         'nonce' => $nonce,
        //         'uniqueId' => $transaction->uniqueId,
        //         'request' => $request,
        //     ];

        //     $assocResponse = self::mainAccountHistory($request, $data);
        //     $response = $assocResponse['response'];
        //     $status = $assocResponse['status'];

        //     var_dump($response);

        //     if ($status == 3 || $status == 7) 
        //     {
        //         $wallet = UserWallet::where([
        //             ['user_id', $transaction->user_id],
        //             ['market', $transaction->ticker]
        //         ]);

        //         if ($wallet->count() == 0) 
        //         {
        //             $transaction->update(['status' => 1]);

        //             UserWallet::create([
        //                 'user_id' => $transaction->user_id,
        //                 'market' => $transaction->ticker,
        //                 'amount' => $response['records'][0]['amount'],
        //             ]);
        //         } 
        //         else 
        //         {
        //             $transaction->update(['status' => 7]);

        //             $amount =  UserWallet::where([
        //                 ['user_id', $transaction->user_id],
        //                 ['market', $transaction->ticker]
        //             ])->first()->amount;

        //             UserWallet::where([
        //                 ['user_id', $transaction->user_id],
        //                 ['market', $transaction->ticker]

        //             ])->update([
        //                 'amount' => $amount + $response['records'][0]['amount'],
        //             ]);
        //         }
        //     }
        // }
    }
}