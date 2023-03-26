<?php

namespace WPTrait\Traits;

trait Session
{

    /**
     * PHP Session
     *
     * @var \WPTrait\Http\Session
     */
    public $session;

    public function __construct()
    {
        $this->session = new \WPTrait\Http\Session();
    }

    public function session($name): \WPTrait\Http\Session
    {
        return new \WPTrait\Http\Session($name);
    }
}