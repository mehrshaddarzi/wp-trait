<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasPostTypeColumns')) {

    trait HasPostTypeColumns
    {
        public $slug;

        public function register_post_type_columns()
        {
            $this->add_filter('manage_' . $this->slug . '_posts_columns', 'columns');
            $this->add_action('manage_' . $this->slug . '_posts_custom_column', 'content_columns', 10, 2);
        }

        public function columns($columns)
        {
            return $columns;
        }

        public function content_columns($column, $post_id)
        {
           global $post;
        }
    }

}