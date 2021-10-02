<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\TaxonomyColumns')) {

    trait TaxonomyColumns
    {
        public $slug;

        public function bootTaxonomyColumns($arg = [])
        {
            $defaults = [
                'method' => 'columns',
                'content_method' => 'content_columns',
                'slug' => (isset($this->slug) ? $this->slug : ''),
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            $this->add_filter('manage_edit-' . $args['slug'] . '_columns', $args['method'], $args['priority']);
            $this->add_filter('manage_' . $args['slug'] . '_custom_column', $args['content_method'], $args['priority'], 3);
        }

        public function columns($columns)
        {
            return $columns;
        }

        public function content_columns($content, $column_name, $term_id)
        {
            return $content;
        }
    }

}