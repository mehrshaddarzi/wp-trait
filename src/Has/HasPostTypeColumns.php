<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasPostTypeColumns')) {

    trait HasPostTypeColumns
    {
        public $slug;

        public function register_post_type_columns($slug = null, $priority = 10)
        {
            $slug = (is_null($slug) ? $this->slug : $slug);
            $this->add_filter('manage_' . $slug . '_posts_columns', 'columns', $priority);
            $this->add_action('manage_' . $slug . '_posts_custom_column', 'content_columns', $priority, 2);
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