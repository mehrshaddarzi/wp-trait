<?php

namespace WPTrait\Http;

use WPTrait\Utils\Arr;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Http\Session')) {

    class Session
    {
        public $name;

        public function __construct($name = null)
        {
            $this->name = $name;
        }

        public function all()
        {
            return ($_SESSION ?? []);
        }

        public function get($default = null, $name = null)
        {
            return Arr::get($this->all(), (is_null($name) ? $this->name : $name), $default);
        }

        public function has($name = null)
        {
            return Arr::has($this->all(), (is_null($name) ? $this->name : $name));
        }

        public function set($name, $value)
        {
            return $_SESSION[$name] = $value;
        }

        public function save($value, $name = null)
        {
            return $this->set((is_null($name) ? $this->name : $name), $value);
        }

        public function delete($name = null)
        {
            $name = (is_null($name) ? $this->name : $name);
            if ($this->has($name)) {
                unset($_SESSION[$name]);
                return true;
            }
            return false;
        }

        public function destroy()
        {
            session_start();
            $_SESSION = [];
            @session_destroy();
            session_write_close();
        }

        public function id()
        {
            return session_id();
        }

    }

}