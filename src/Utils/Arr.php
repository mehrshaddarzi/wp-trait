<?php

namespace WPTrait\Utils;

class Arr
{

    public static function alias($array = [], $alias = [])
    {
        $_array = [];
        foreach ($array as $key => $value) {
            $_array[(isset($alias[$key]) ? $alias[$key] : $key)] = $value;
        }

        return $_array;
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

}