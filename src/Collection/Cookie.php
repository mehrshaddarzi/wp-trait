<?php

namespace WPTrait\Collection;

use WPTrait\Hook\Constant;
use WPTrait\Utils\Arr;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Collection\Cookie')) {

    class Cookie
    {
        use Constant;

        public $name;

        public function __construct($name = null)
        {
            $this->name = $name;
        }

        public function all()
        {
            return (isset($_COOKIE) ? $_COOKIE : []);
        }

        public function get($default = null, $name = null)
        {
            $data = Arr::get($this->all(), (is_null($name) ? $this->name : $name), $default);
            if (is_string($data) && is_array(json_decode(stripslashes_deep($data), true))) {
                return json_decode(stripslashes_deep($data));
            }

            return $data;
        }

        public function has($name = null)
        {
            return Arr::has($this->all(), (is_null($name) ? $this->name : $name));
        }

        public function set($name, $value, $expire = '', $sanitize = true)
        {
            return setcookie($name, ($sanitize === true ? $this->sanitizeCookieValue($value) : $value), time() + ($expire == "" ? $this->constant('hour') : $expire), COOKIEPATH, COOKIE_DOMAIN);
        }

        public function save($value, $expire = '', $sanitize = true, $name = null)
        {
            return $this->set((is_null($name) ? $this->name : $name), $value, $expire, $sanitize);
        }

        public function delete($name = null)
        {
            $name = (is_null($name) ? $this->name : $name);
            if ($this->has($name)) {
                return setcookie($name, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
            }
            return false;
        }

        private function sanitizeCookieValue($value)
        {
            if (is_array($value) || is_object($value)) {
                return json_encode((array)$value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            return $value;
        }

    }

}