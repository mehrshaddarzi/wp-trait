<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasFrontAssets')) {

    trait HasFrontAssets
    {
        use Assets;

        public function register_front_assets($priority = 10, $method = 'wp_enqueue_scripts')
        {
            $this->add_action('wp_enqueue_scripts', $method, $priority);
        }

        public function wp_enqueue_scripts($hook_suffix)
        {
            // $hook_suffix == global $pagenow;
        }
    }

}