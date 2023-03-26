<?php

namespace WPTrait\Collection;

use WPTrait\Http\Request;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\Nonce')) {

    class Nonce
    {

        public $action;

        public function __construct($action = -1)
        {
            $this->action = $action;
        }

        public function create($action = -1)
        {
            return wp_create_nonce((is_null($action) ? $this->action : $action));
        }

        public function verify($input = '', $action = -1)
        {
            # (int|false)
            $request = new Request();
            return wp_verify_nonce($request->input($input), (is_null($action) ? $this->action : $action));
        }

        public function input($name = '_wpnonce', $action = -1, $referer = true, $echo = true)
        {
            return wp_nonce_field((is_null($action) ? $this->action : $action), $name, $referer, $echo);
        }
    }

}