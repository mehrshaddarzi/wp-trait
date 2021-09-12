<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasAdminInit')) {

    trait HasAdminInit
    {

        public function register_admin_init($priority = 10, $method = 'admin_init')
        {
            $this->add_action('admin_init', $method, $priority);
        }

        public function admin_init()
        {
        }
    }

}