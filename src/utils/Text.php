<?php

namespace App\src\utils;

use League\HTMLToMarkdown\HtmlConverter;
use League\CommonMark\CommonMarkConverter;

class Text
{
    public static function excerpt(?string $content, int $limit = 20) : ?string
    {
        $content = strip_tags($content);
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

    public static function HtmlToMarkdown(string $html) : string
    {
        $htmlToMarkdownConverter = new HtmlConverter([
            'strip_tags'=> true,
            'hard_break'=> true
        ]);
        return $htmlToMarkdownConverter->convert($html);
    }

    public static function markdownToHtml(string $markdown) : string
    {
        $markdownToHtmlConverter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        return $markdownToHtmlConverter->convertToHtml($markdown);
    }

    public static function removeMarkdown(string $markdown) : string
    {
        return strip_tags(self::markdownToHtml($markdown));
    }
}