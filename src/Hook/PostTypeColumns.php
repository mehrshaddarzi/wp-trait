<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\PostTypeColumns')) {

    trait PostTypeColumns
    {
        public $slug;

        public function bootPostTypeColumns($arg = [])
        {
            $defaults = [
                'method' => 'columns',
                'content_method' => 'content_columns',
                'slug' => $this->slug,
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            $this->add_filter('manage_' . $args['slug'] . '_posts_columns', $args['method'], $args['priority']);
            $this->add_action('manage_' . $args['slug'] . '_posts_custom_column', $args['content_method'], $args['priority'], 2);
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