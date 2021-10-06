<?php

namespace App\Src\Model;

use App\Src\Utils\{Text, Date};

class Model
{
    public function getExcerpt($content , $limit)
    {
        return Text::excerpt($content, $limit);
    }

    public function getFormatedDate($date)
    {
        if ($date !== null) {
            return Date::formatedDate($date);            
        }
        return null;
    }
}