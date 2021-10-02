<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\ViewsSub')) {

    trait ViewsSub
    {
        public $slug;

        public function bootViewsSub($arg = [])
        {
            $defaults = [
                'method' => 'views_edit_sub',
                'screen_id' => (isset($this->slug) ? 'edit-' . $this->slug : ''),
                'priority' => 10
            ];
            $args = wp_parse_args($arg, $defaults);

            //@see https://developer.wordpress.org/reference/hooks/views_this-screen-id/
            $this->add_filter('views_' . $args['screen_id'], $args['method'], $args['priority']);
        }

        public function views_edit_sub($views)
        {
            return $views;
        }
    }

}