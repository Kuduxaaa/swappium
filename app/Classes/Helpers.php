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
}