<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasSortableColumns')) {

    trait HasSortableColumns
    {
        public $slug;

        public function register_sortable_columns()
        {
            $this->add_filter('manage_edit-' . $this->slug . '_sortable_columns', 'sortable_columns');
        }

        public function sortable_columns($columns)
        {
            return $columns;
        }
    }

}