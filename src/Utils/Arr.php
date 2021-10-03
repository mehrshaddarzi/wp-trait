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


}