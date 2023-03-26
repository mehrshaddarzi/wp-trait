<?php

namespace WPTrait\Traits;

trait Cookie
{

    public function cookie($name): \WPTrait\Http\Cookie
    {
        return new \WPTrait\Http\Cookie($name);
    }
}