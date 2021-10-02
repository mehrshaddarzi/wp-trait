<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\Init')) {

    trait Init
    {

        public function bootInit($arg = [])
        {
            $defaults = [
                'method' => 'init',
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            $this->add_action('init', $args['method'], $args['priority']);
        }

        public function init()
        {
        }
    }

}