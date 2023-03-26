<?php

namespace WPTrait\Traits;

trait Cookie
{

    /**
     * PHP Cookie
     *
     * @var \WPTrait\Http\Cookie
     */
    public $cookie;

    public function __construct()
    {
        $this->cookie = new \WPTrait\Http\Cookie();
    }

    public function cookie($name): \WPTrait\Http\Cookie
    {
        return new \WPTrait\Http\Cookie($name);
    }
}