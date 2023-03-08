<?php

namespace WPTrait\Collection;

use WPTrait\Utils\Arr;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\Option')) {

    class Option
    {

        public $name;

        public function __construct($name = null)
        {
            $this->name = $name;
        }

        public function get($default = false, $name = null)
        {
            $name = (is_null($name) ? $this->name : $name);
            $explode = explode(".", trim($name));
            if (count($explode) > 1) {
                $name = $explode[0];
                array_shift($explode);
                return Arr::get(get_option($name, $default), implode(".", $explode), $default);
            }

            return get_option($name, $default);
        }

        public function delete($name = null)
        {
            return delete_option((is_null($name) ? $this->name : $name));
        }

        public function update($value, $autoload = 'yes', $name = null)
        {
            return update_option((is_null($name) ? $this->name : $name), $value, $autoload);
        }

        public function save(...$args)
        {
            return $this->update(...$args);
        }

        public function add($name, $value = '', $autoload = 'yes')
        {
            return add_option($name, $value, $autoload);
        }
    }
}
