<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Cache')) {

    class Cache
    {

        public function get($key, $group = '', $force = false, $found = null)
        {
            return wp_cache_get($key, $group, $force, $found);
        }

        public function delete($key, $group = '')
        {
            return wp_cache_delete($key, $group);
        }

        public function add($key, $data, $group = '', $expire = 0)
        {
            return wp_cache_add($key, $data, $group, $expire);
        }
    }
}