<?php

namespace App\Src\Utils;

use App\Config\Parameter;

class CSRF
{
    const REFRESH_TIME = 600 ; // 600 seconds = 10 minutes

    public function generateToken()
    {
        return md5(bin2hex(random_bytes(64)));
    }
}