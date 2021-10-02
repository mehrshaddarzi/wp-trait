<?php

namespace WPTrait\Hook;

use WPTrait\Collection\Assets;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\FrontAssets')) {

    trait FrontAssets
    {
        use Assets;

        public function bootFrontAssets($arg = [])
        {
            $defaults = [
                'method' => 'wp_enqueue_scripts',
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            $this->add_action('wp_enqueue_scripts', $args['method'], $args['priority']);
        }

        public function wp_enqueue_scripts($hook_suffix)
        {
            // $hook_suffix == global $pagenow;
        }
    }

}