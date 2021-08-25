<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasNotice')) {

    trait HasNotice
    {

        public function register_notice()
        {
            // Admin Page Notice
            $this->add_action('admin_notices', 'notices');
        }

        public function notices()
        {

        }

        public function show($text, $model = "info", $close_button = true, $echo = true, $style_extra = '')
        {
            $content = '<div class="notice notice-' . $model . '' . ($close_button === true ? " is-dismissible" : "") . '">';
            $content .= '<div style="' . $style_extra . ' inline">' . $text . '</div>';
            $content .= '</div>';
            if ($echo) {
                echo $content;
            } else {
                return $content;
            }
        }
    }

}