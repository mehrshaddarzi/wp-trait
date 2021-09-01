<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasUserColumns')) {

    trait HasUserColumns
    {
        public $slug;

        public function register_user_columns()
        {
            $this->add_filter('manage_users_columns', 'columns');
            $this->add_action('manage_users_custom_column', 'content_columns', 10, 3);
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