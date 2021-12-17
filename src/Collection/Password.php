<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\Password')) {

    class Password
    {
        /**
         * User ID
         *
         * @var int
         */
        public $user_id;

        public function __construct($user_id = null)
        {
            $this->user_id = $user_id;
        }

        public function check($password, $hash = '')
        {
            if (empty($hash) and !empty($this->user_id)) {
                $user_data = get_userdata($this->user_id);
                $hash = $user_data->user_pass;
            }
            return wp_check_password($password, $hash);
        }

        public function set($password, $user_id = '')
        {
            return wp_set_password($password, (empty($user_id) ? $this->user_id : $user_id));
        }

        public function hash($password)
        {
            return wp_hash_password($password);
        }

        public function generate($length = 12, $special_chars = true, $extra_special_chars = false)
        {
            return wp_generate_password($length, $special_chars, $extra_special_chars);
        }

    }
}
