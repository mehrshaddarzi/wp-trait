<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasBulkActions')) {

    trait HasBulkActions
    {
        public $slug;

        public function register_bulk_actions($priority = 10)
        {

            // Admin Bulk Action
            // @see https://awhitepixel.com/blog/wordpress-admin-add-custom-bulk-action/
            $this->add_filter('bulk_actions-edit-' . $this->slug, 'bulk_actions', $priority);
            $this->add_filter('handle_bulk_actions-edit-' . $this->slug, 'handle_bulk_actions', $priority, 3);
        }

        public function bulk_actions($bulk_actions)
        {
            return $bulk_actions;
        }

        public function handle_bulk_actions($redirect_url, $action, $item_ids)
        {
            return $redirect_url;
        }
    }

}