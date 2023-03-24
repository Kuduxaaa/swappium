<?php

namespace App\Classes;

class Helpers
{
    static function generateIdentifier($email) {
        return md5(sha1($email));
    }

    static function generateUserUniqueID($email) 
    {
        return rand(1, 999999) . self::generateIdentifier($email);
    }

    static function calculatePercentage($amount, $percentage) 
    {
        return $amount - ($amount * ($percentage / 100));
    }

    static function getFiatCurrencies()
    {
        return ['USD','EUR','GBP','JPY','CAD','AUD','CHF','CNY','HKD','NZD','SEK','KRW','SGD','NOK','MXN','INR','RUB','ZAR','TRY','BRL','TWD','DKK','PLN','THB'];
    }

    static function guidv4($data)
    {
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    static function getStatus($id) 
    {
        $id = intval($id);

        $codes = [
            'Pending' => [1, 2, 6, 10, 11, 12, 13, 14, 15, 16, 17],
            'Success' => [3, 7],
            'Canceled' => [4, 9],
            'Unconfirmed by user' => [5],
            'Uncredited' => [22],
            'Partially successful' => [18]
        ];

        foreach($codes as $value => $code) 
        {
            if (in_array($id, $code)) 
            {
                return $value;
            }
        }

        return 'Unknown';
    }
}