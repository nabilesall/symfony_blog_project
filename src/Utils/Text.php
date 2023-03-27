<?php

namespace App\Utils;

class Text
{
    public static function excerpt(string $content, int $limit = 400)
    {
        if (mb_strlen($content) <= $limit) {
            return $content;
        }

        $lastSpace = mb_strpos($content, ' ', $limit);
        return trim(mb_substr($content, 0, $lastSpace)).'...';
    }
}