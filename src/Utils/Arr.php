<?php

namespace WPTrait\Utils;

class Arr
{

    public static function alias($arr = [], $alias = [])
    {
        $array = [];
        foreach ($arr as $key => $value) {
            $array[(isset($alias[$key]) ? $alias[$key] : $key)] = $value;
        }

        return $array;
    }

    public static function has(array $array, string $key): bool
    {
        return NULL !== self::get($array, $key);
    }

    public static function get(array $array, string $key, $default = NULL)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }

            $array = $array[$segment];
        }

        return $array;
    }

    public static function except(array $array, $keys)
    {
        return array_diff_key($array, array_flip((array)$keys));
    }

    public static function only(array $array, $keys)
    {
        return array_intersect_key($array, array_flip((array)$keys));
    }

    public static function join($separator, $array): string
    {
        return implode($separator, $array);
    }

    public static function isAssoc($arr): bool
    {
        if (!is_array($arr) || array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

}