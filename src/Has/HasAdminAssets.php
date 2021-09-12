<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasAdminAssets')) {

    trait HasAdminAssets
    {
        use Assets;

        public function register_admin_assets($priority = 10, $method = 'admin_enqueue_scripts')
        {
            $this->add_action('admin_enqueue_scripts', $method, $priority);
        }

        public function admin_enqueue_scripts($hook_suffix)
        {
            // $hook_suffix == global $pagenow;
        }
    }

}