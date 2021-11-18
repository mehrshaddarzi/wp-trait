<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\WP')) {

    trait WP
    {

        public function bootWP($arg = [])
        {
            $defaults = [
                'method' => 'wp',
                'priority' => 10
            ];
            $args = wp_parse_args($arg, $defaults);

            $this->add_action('wp', $args['method'], $args['priority']);
        }

        public function wp()
        {
        }
    }

}