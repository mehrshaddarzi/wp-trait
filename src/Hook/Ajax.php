<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('Ajax')) {

    trait Ajax
    {
        public function bootAjax($arg = array())
        {
            $defaults = array(
                'methods' => array(),
                'priority' => 10,
            );
            $args = wp_parse_args($arg, $defaults);

            if (is_array($args['methods'])) {
                foreach ($args['methods'] as $method) {
                    $this->add_action('wp_ajax_nopriv_' . $method, 'admin_ajax_' . $method, $args['priority']);
                }
            }
        }

        public function is_ajax_request()
        {
            return wp_doing_ajax();
        }

        public function ajax_url($action = '', $args = array())
        {
            return add_query_arg(array_merge(array('action' => $action), $args), admin_url('admin-ajax.php'));
        }

    }

}