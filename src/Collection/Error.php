<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\Error')) {

    class Error
    {
        public function has($thing)
        {
            return is_wp_error($thing);
        }

        public function message($thing)
        {
            return $thing->get_error_message();
        }

        public function messages($thing)
        {
            return $thing->get_error_messages();
        }

        public function code($thing)
        {
            return $thing->get_error_code();
        }

        public function codes($thing)
        {
            return $thing->get_error_codes();
        }

        public function new($code = '', $message = '', $data = '')
        {
            #see: https://code.tutsplus.com/tutorials/wordpress-error-handling-with-wp_error-class-i--cms-21120
            return new \WP_Error($code, $message, $data);
        }
    }

}
