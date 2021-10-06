<?php

namespace App\Src\Utils;

class Date
{
    public static function formatedDate($datetime) : string
    {
        $date = new \DateTime($datetime);
        $day = $date->format('d/m/Y');
        $time = $date->format('H:i');
        return $day . " à " . $time;
    }
}