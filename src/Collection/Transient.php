<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\Transient')) {

    class Transient
    {

        public $name;

        public function __construct($name = null)
        {
            $this->name = $name;
        }

        public function get($name = null)
        {
            return get_transient((is_null($name) ? $this->name : $name));
        }

        public function delete($name = null)
        {
            return delete_transient((is_null($name) ? $this->name : $name));
        }

        public function add($name, $value, $expiration = 0)
        {
            return set_transient($name, $value, $expiration);
        }

        public function set(...$args)
        {
            $this->add(...$args);
        }

        public function remember($key, $callback, $expire = 0)
        {
            $cached = $this->get($key);
            if (false !== $cached) {
                return $cached;
            }

            $value = $callback();

            if (!is_wp_error($value)) {
                $this->add($key, $value, $expire);
            }

            return $value;
        }

        public function forget($key, $default = null)
        {
            $cached = $this->get($key);

            if (false !== $cached) {
                $this->delete($key);
                return $cached;
            }

            return $default;
        }
    }
}