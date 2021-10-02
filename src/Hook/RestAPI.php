<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\RestAPI')) {

    trait RestAPI
    {

        public function bootRestAPI($arg = [])
        {
            $defaults = [
                'method' => 'rest_api_init',
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            $this->add_action('rest_api_init', $args['method'], $args['priority']);
        }

        public function rest_api_init()
        {
        }
    }

}