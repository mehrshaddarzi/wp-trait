<?php

namespace WPTrait\Traits;

trait Session
{

    public function session($name): \WPTrait\Http\Session
    {
        return new \WPTrait\Http\Session($name);
    }
}