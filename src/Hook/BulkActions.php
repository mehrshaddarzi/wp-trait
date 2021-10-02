<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\BulkActions')) {

    trait BulkActions
    {
        public $slug;

        public function bootBulkActions($arg = [])
        {
            $defaults = [
                'method' => 'bulk_actions',
                'handle_method' => 'handle_bulk_actions',
                'slug' => $this->slug,
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            // Admin Bulk Action
            // @see https://awhitepixel.com/blog/wordpress-admin-add-custom-bulk-action/
            $this->add_filter('bulk_actions-edit-' . $args['slug'], $args['method'], $args['priority']);
            $this->add_filter('handle_bulk_actions-edit-' . $args['slug'], $args['handle_method'], $args['priority'], 3);
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