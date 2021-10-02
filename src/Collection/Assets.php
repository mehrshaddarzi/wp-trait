<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Collection\Assets')) {

    trait Assets
    {
        public function add_script($handle, $src, $deps = [], $ver = false, $in_footer = false, $enqueue = true, $localize = [], $inline_script = '')
        {
            wp_register_script($handle, $src, $deps, $this->get_asset_version($ver), $in_footer);
            if ($enqueue) {
                wp_enqueue_script($handle);
            }

            if (!empty($localize)) {
                wp_localize_script($handle, $localize['variable'], $localize['data']);
            }

            if (!empty($inline_script)) {
                wp_add_inline_script($handle, $inline_script);
            }
        }

        public function add_style($handle, $src, $deps = [], $ver = false, $media = 'all', $enqueue = true, $inline_style = '')
        {
            wp_register_style($handle, $src, $deps, $this->get_asset_version($ver), $media);

            if ($enqueue) {
                wp_enqueue_style($handle);
            }

            if (!empty($inline_script)) {
                wp_add_inline_style($handle, $inline_style);
            }
        }

        public function get_asset_version($ver = false)
        {
            if (defined('SCRIPT_DEBUG') and SCRIPT_DEBUG === true) {
                return time();
            }

            return $ver;
        }
    }

}