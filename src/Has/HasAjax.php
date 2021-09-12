<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasAjax')) {

    trait HasAjax
    {

        public function register_admin_ajax($actions = array(), $priority = 10)
        {
            if (is_array($actions)) {
                foreach ($actions as $method) {
                    $this->add_action('wp_ajax_' . $method, 'admin_ajax_' . $method, $priority);
                    $this->add_action('wp_ajax_nopriv_' . $method, 'admin_ajax_' . $method, $priority);
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