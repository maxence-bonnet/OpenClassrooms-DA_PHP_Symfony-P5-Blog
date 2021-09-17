<?php

namespace App\src\model;

use App\src\utils\Text;
use App\src\utils\Date;

class Model
{
    public function getExcerpt($content , $limit)
    {
        return Text::excerpt($content, $limit);
    }

    public function getFormatedDate($date)
    {
        if($date !== null){
            return Date::formatedDate($date);            
        }
        return null;
    }
}