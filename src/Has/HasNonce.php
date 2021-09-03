<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasNonce')) {

    trait HasNonce
    {

        public function create_nonce($action = -1)
        {
            return wp_create_nonce($action);
        }

        public function verify_nonce($value, $action = -1)
        {
            # (int|false)
            return wp_verify_nonce($value, $action);
        }

        public function create_nonce_input($action = -1, $name = '_wpnonce', $referer = true, $echo = true)
        {
            return wp_nonce_field($action, $name, $referer, $echo);
        }
    }

}