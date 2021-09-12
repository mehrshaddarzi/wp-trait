<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasInit')) {

    trait HasInit
    {

        public function register_front_init($priority = 10, $method = 'init')
        {
            $this->add_action('init', $method, $priority);
        }

        public function init()
        {
        }
    }

}