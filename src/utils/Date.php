<?php

namespace App\src\utils;

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