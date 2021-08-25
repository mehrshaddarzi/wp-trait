<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasPreGetQuery')) {

    trait HasPreGetQuery
    {

        public function register_pre_get_query($type = 'posts')
        {
            /**
             * Type:
             *
             * posts (https://developer.wordpress.org/reference/hooks/pre_get_posts/)
             * terms (https://developer.wordpress.org/reference/hooks/pre_get_terms/)
             * users (https://developer.wordpress.org/reference/hooks/pre_get_users/)
             */
            $this->add_action('pre_get_' . $type, 'pre_get_' . $type);
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