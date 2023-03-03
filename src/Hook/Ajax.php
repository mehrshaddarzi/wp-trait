<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\Ajax')) {

    trait Ajax
    {
        public function bootAjax($arg = [])
        {
            $defaults = [
                'methods' => [],
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            if (is_array($args['methods'])) {
                foreach ($args['methods'] as $method) {
                    $this->add_action('wp_ajax_nopriv_' . $method, 'admin_ajax_' . $method, $args['priority']);
                    $this->add_action('wp_ajax_' . $method, 'admin_ajax_' . $method, $args['priority']);
                }
            }
        }

        public function ajax_url($action = '', $args = [])
        {
            return add_query_arg(array_merge(['action' => $action], $args), admin_url('admin-ajax.php'));
        }

    }

}