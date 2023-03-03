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
}