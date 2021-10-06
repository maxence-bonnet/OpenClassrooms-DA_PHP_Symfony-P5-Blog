<?php

namespace App\Src\Utils;

class URL
{
    public static function mergeOn(array $data, array $parameter) : string
    {
        // custom callback to preserve cases where value is 0 / "0"
        return "?" . http_build_query(array_merge(array_filter($data, fn($value) => $value !== null && $value !== ''), $parameter));   
    }
}