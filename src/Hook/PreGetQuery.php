<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\PreGetQuery')) {

    trait PreGetQuery
    {

        public function bootPreGetQuery($arg = [])
        {
            $defaults = [
                'type' => 'posts',
                'method' => '',
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            /**
             * Type:
             *
             * posts (https://developer.wordpress.org/reference/hooks/pre_get_posts/)
             * terms (https://developer.wordpress.org/reference/hooks/pre_get_terms/)
             * users (https://developer.wordpress.org/reference/hooks/pre_get_users/)
             * comments (https://developer.wordpress.org/reference/hooks/pre_get_comments/)
             */
            $this->add_action('pre_get_' . $args['type'], ($args['method'] == "" ? 'pre_get_' . $args['type'] : $args['method']), $args['priority']);
        }

        public function pre_get_posts($query)
        {

        }

        public function pre_get_users($query)
        {

        }

        public function pre_get_terms($query)
        {

        }

    }

}