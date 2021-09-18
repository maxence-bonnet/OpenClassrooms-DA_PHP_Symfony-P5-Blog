<?php

namespace App\src\utils;

class Text
{
    public static function excerpt(?string $content, int $limit = 20) : ?string
    {
        if(mb_strlen($content) <= $limit){
            return $content;
        }
        $lastSpace =  mb_strpos($content, ' ', $limit);
        if(!$lastSpace){
            $lastSpace =  mb_strpos($content, PHP_EOL , $limit);
            if(!$lastSpace){
                $lastSpace =  mb_strpos($content, "\t" , $limit);
                if(!$lastSpace){
                    $lastSpace = $limit;
                }
            }
        }
        if(mb_strlen(mb_substr($content, 0, $lastSpace)) > $limit + 10){
            return mb_substr($content, 0, $limit + 10) . '...';
        }
        return mb_substr($content, 0, $lastSpace) . '...';        
    }

    public static function formatMailMessage(string $message) : string
    {
        return str_replace("\n.", "\n..", wordwrap(nl2br(htmlspecialchars($message)), 70, "\r\n"));
    }
}