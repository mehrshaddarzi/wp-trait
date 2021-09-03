<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasAdminAssets')) {

    trait HasAdminAssets
    {
        use Assets;

        public function register_admin_assets()
        {
            $this->add_action('admin_enqueue_scripts', 'admin_enqueue_scripts');
        }

        public function admin_enqueue_scripts($hook_suffix)
        {
            // $hook_suffix == global $pagenow;
        }
    }

}