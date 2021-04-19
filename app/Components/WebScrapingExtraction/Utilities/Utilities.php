<?php

namespace App\Components\WebScrapingExtraction\Utilities;

class Utilities
{

    public static function onlyNumbers($string){
        return preg_replace("/[^0-9]/","", $string);
    }

    public static function onlyFloat($string)
    {
        return preg_replace('/[^0-9][^0-9.]*[^0-9,]*[^0-9]/i', '', $string);
    }

    public static function usCoin($coin) {
        $coin = preg_replace('/[^0-9][^0-9.]*[^0-9,]*[^0-9]/i', '', $coin);
        return str_replace(",", ".", str_replace(".", "", $coin));
    }

    public static function is_base64($s)
    {
        // Check if there are valid base64 characters
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s)) return false;

        // Decode the string in strict mode and check the results
        $decoded = base64_decode($s, true);
        if(false === $decoded) return false;

        // Encode the string again
        if(base64_encode($decoded) != $s) return false;

        return true;
    }

    public static function replaceLinkBase64(string $base64) : string
    {
        return preg_replace('/^data:[^;]+[^;]+;base64,/', '', $base64);
    }
}