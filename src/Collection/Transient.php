<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Transient')) {

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
    }
}