<?php

namespace WPTrait\Hook;

use WPTrait\Collection\Assets;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\AdminAssets')) {

    trait AdminAssets
    {
        use Assets;

        public function bootAdminAssets($arg = [])
        {
            $defaults = [
                'method' => 'admin_enqueue_scripts',
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            $this->add_action('admin_enqueue_scripts', $args['method'], $args['priority']);
        }

        public function admin_enqueue_scripts($hook_suffix)
        {
            // $hook_suffix == global $pagenow;
        }
    }

}