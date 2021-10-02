<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\UserColumns')) {

    trait UserColumns
    {

        public function bootUserColumns($arg = [])
        {
            $defaults = [
                'method' => 'columns',
                'content_method' => 'content_columns',
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            $this->add_filter('manage_users_columns', $args['method'], $args['priority']);
            $this->add_action('manage_users_custom_column', $args['content_method'], $args['priority'], 3);
        }

        public function columns($columns)
        {
            return $columns;
        }

        public function content_columns($value, $column_name, $user_id)
        {
        }
    }

}