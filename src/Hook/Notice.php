<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\Notice')) {

    trait Notice
    {

        public function bootNotice($arg = [])
        {
            $defaults = [
                'method' => 'admin_notices',
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            // Admin Page Notice
            $this->add_action('admin_notices', $args['method'], $args['priority']);
        }

        public function admin_notices()
        {

        }

        public function add_alert($text, $model = "success", $close_button = true, $echo = false, $style_extra = 'padding: 12px;')
        {
            $content = '<div class="notice notice-' . $model . '' . ($close_button === true ? " is-dismissible" : "") . '">';
            $content .= '<div style="' . $style_extra . '">' . $text . '</div>';
            $content .= '</div>';
            if ($echo) {
                echo $content;
            } else {
                return $content;
            }
        }

        public function remove_query_arg_url($args = [])
        {
            $_SERVER['REQUEST_URI'] = remove_query_arg($args);
        }

        public function inline_admin_notice($alert, $page_url_args = [], $priority = 10)
        {
            if (!empty($page_url_args)) {
                $this->remove_query_arg_url($page_url_args);
            }
            add_action('admin_notices', function () use ($alert) {
                echo $alert;
            }, $priority);
        }
    }

}