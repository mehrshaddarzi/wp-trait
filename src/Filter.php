<?php

namespace WPTrait;

use WPTrait\Interfaces\Hooks;

if (!class_exists('WPTrait\Filter')) {

    class Filter implements Hooks
    {

        public function add($hook_name, $callback, $priority = 10, $accepted_args = 1)
        {
            return add_filter($hook_name, $callback, $priority, $accepted_args);
        }

        public function remove($hook_name, $callback, $priority = 10)
        {
            return remove_filter($hook_name, $callback, $priority);
        }

        public function apply(...$args)
        {
            return apply_filters(...$args);
        }

        public function current()
        {
            return current_filter();
        }

        public function all()
        {
            return $GLOBALS['wp_filter'];
        }

        public function has($hook_name, $callback = false)
        {
            return has_filter($hook_name, $callback);
        }

        public function doing($hook_name = null)
        {
            return doing_filter($hook_name);
        }

        public function reset($hook_name, $priority = false)
        {
            return remove_all_filters($hook_name, $priority);
        }

    }

}