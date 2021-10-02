<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\SortableColumns')) {

    trait SortableColumns
    {
        public $slug;

        public function bootSortableColumns($arg = [])
        {
            $defaults = [
                'method' => 'sortable_columns',
                'slug' => (isset($this->slug) ? $this->slug : ''),
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            $this->add_filter('manage_edit-' . $args['slug'] . '_sortable_columns', $args['method'], $args['priority']);
        }

        public function sortable_columns($columns)
        {
            return $columns;
        }
    }

}