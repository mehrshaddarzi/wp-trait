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

        public function all()
        {
            return (isset($_COOKIE) ? $_COOKIE : []);
        }

        public function get($name = '', $default = null)
        {
            $data = Arr::get($this->all(), $name, $default);
            if (is_string($data) && is_array(json_decode($data, true))) {
                return json_decode($data);
            }

            return $data;
        }

        public function has($name = '')
        {
            return Arr::has($this->all(), $name);
        }

        public function set($name, $value, $expire = '', $sanitize = true)
        {
            return setcookie($name, ($sanitize === true ? $this->sanitizeCookieValue($value) : $value), time() + ($expire == "" ? $this->constant('hour') : $expire), COOKIEPATH, COOKIE_DOMAIN);
        }

        public function delete($name)
        {
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