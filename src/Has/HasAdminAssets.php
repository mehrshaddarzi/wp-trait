<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasAdminAssets')) {

    trait HasAdminAssets
    {

        public function register_admin_assets()
        {
            $this->add_action('admin_enqueue_scripts', 'admin_enqueue_scripts');
        }

        public function admin_enqueue_scripts($hook_suffix)
        {
        }

        public function add_script($handle, $src, $deps = array(), $ver = false, $in_footer = false, $enqueue = true, $localize = array(), $inline_script = '')
        {
            wp_register_script($handle, $src, $deps, $ver, $in_footer);
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

        public function add_style($handle, $src, $deps = array(), $ver = false, $media = 'all', $enqueue = true, $inline_style = '')
        {
            wp_register_style($handle, $src, $deps, $ver, $media);

            if ($enqueue) {
                wp_enqueue_style($handle);
            }

            if (!empty($inline_script)) {
                wp_add_inline_style($handle, $inline_style);
            }
        }
    }

}