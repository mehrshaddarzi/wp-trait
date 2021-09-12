<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasTaxonomyColumns')) {

    trait HasTaxonomyColumns
    {
        public $slug;

        public function register_taxonomy_columns($priority = 10)
        {
            $this->add_filter('manage_edit-' . $this->slug . '_columns', 'columns', $priority);
            $this->add_filter('manage_' . $this->slug . '_custom_column', 'content_columns', $priority, 3);
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