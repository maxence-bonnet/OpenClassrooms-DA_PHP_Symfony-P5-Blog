<?php

namespace App\src\utils;

class URL
{
    public static function currentGet()
    {
        return http_build_query($_GET);
    }

    public static function mergeOn(array $data, array $parameter) : string
    {
        return "?" . http_build_query(array_merge(array_filter($data, fn($value) => !is_null($value) && $value !== ''), $parameter));
        // custom callback to preserve cases where value is 0 / "0"
    }
}