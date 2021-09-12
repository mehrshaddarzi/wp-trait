<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasAdminFooter')) {

    trait HasAdminFooter
    {

        public function register_admin_footer($priority = 10, $method = 'admin_footer')
        {
            $this->add_action('admin_footer', $method, $priority);
        }

        public function admin_footer()
        {
        }
    }

}