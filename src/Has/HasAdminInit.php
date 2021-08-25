<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasAdminInit')) {

    trait HasAdminInit
    {

        public function register_admin_init()
        {
            $this->add_action('admin_init', 'admin_init');
        }

        public function admin_init()
        {
        }
    }

}