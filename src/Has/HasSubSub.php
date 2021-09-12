<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasSubSub')) {

    trait HasSubSub
    {
        public $slug;

        public function register_views_sub($priority = 10, $method = 'views_edit_sub')
        {
            $this->add_filter('views_edit-' . $this->slug, $method, $priority);
        }

        public function views_edit_sub($views)
        {
            return $views;
        }

    }

}