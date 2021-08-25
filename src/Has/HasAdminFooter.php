<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasAdminFooter')) {

    trait HasAdminFooter
    {

        public function register_admin_init()
        {
            $this->add_action('admin_footer', 'admin_footer');
        }

        public function admin_footer()
        {
        }
    }

}