<?php

namespace WPTrait\Traits;

trait Session
{

    public function session($name = null): \WPTrait\Http\Session
    {
        return new \WPTrait\Http\Session($name);
    }
}