<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\AdminFooter')) {

    trait AdminFooter
    {

        public function bootAdminFooter($arg = [])
        {
            $defaults = [
                'method' => 'admin_footer',
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            $this->add_action('admin_footer', $args['method'], $args['priority']);
        }

        public function admin_footer()
        {
        }
    }

}