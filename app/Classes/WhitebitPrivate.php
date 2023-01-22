<?php

namespace App\Classes;

class WhitebitPrivate extends WhitebitPublic
{
    static function test()
    {
        return self::getMainAddress('BTC');
    }

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
}