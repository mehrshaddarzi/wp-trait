<?php

namespace WPTrait\Utils;

class Str
{

    public static function explode($sep, $string, $limit = PHP_INT_MAX): array
    {
        return explode($sep, $string, $limit);
    }

    public static function length($value, $encoding = null): int
    {
        return mb_strlen($value, $encoding);
    }

    public static function limit($value, $limit = 100, $end = '...')
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }

        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
    }

    public static function replace($search, $replace, $subject): array|string
    {
        return str_replace($search, $replace, $subject);
    }

    public static function contains($haystack, $needles, $ignoreCase = false): bool
    {
        if ($ignoreCase) {
            $haystack = mb_strtolower($haystack);
        }

        if (!is_iterable($needles)) {
            $needles = (array)$needles;
        }

        foreach ($needles as $needle) {
            if ($ignoreCase) {
                $needle = mb_strtolower($needle);
            }

            if ($needle !== '' && str_contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    public static function lower($value): string
    {
        return mb_strtolower($value, 'UTF-8');
    }

    public static function camel($str, $sep = ["-", "_"]): string
    {
        $str = preg_replace('/([a-z])([A-Z])/', "\\1 \\2", $str);
        $str = preg_replace('@[^a-zA-Z0-9\-_ ]+@', '', $str);
        $str = str_replace($sep, ' ', $str);
        $str = str_replace(' ', '', ucwords(strtolower($str)));
        return strtolower(substr($str, 0, 1)) . substr($str, 1);
    }

}