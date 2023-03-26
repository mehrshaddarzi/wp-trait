<?php

namespace WPTrait;

use WPTrait\Interfaces\Hooks;

if (!class_exists('WPTrait\Action')) {

    class Action implements Hooks
    {

        public function add($hook_name, $callback, $priority = 10, $accepted_args = 1)
        {
            return add_action($hook_name, $callback, $priority, $accepted_args);
        }

        public function remove($hook_name, $callback, $priority = 10)
        {
            return remove_action($hook_name, $callback, $priority);
        }

        public function do(...$args)
        {
            return do_action(...$args);
        }

        public function current()
        {
            return current_action();
        }

        public function all()
        {
            return $GLOBALS['wp_actions'];
        }

        public function has($hook_name, $callback = false)
        {
            return has_action($hook_name, $callback);
        }

        public function doing($hook_name = null)
        {
            return doing_action($hook_name);
        }

        public function did($hook_name)
        {
            return did_action($hook_name);
        }

        public function reset($hook_name, $priority = false)
        {
            return remove_all_actions($hook_name, $priority);
        }

    }

}