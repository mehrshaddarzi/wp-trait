<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\ImageSize')) {

    trait ImageSize
    {

        public function bootImageSize($arg = [])
        {
            # Setup Image Size
            $defaults = [
                'method' => 'setup_image_size',
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);
            $this->add_action('after_setup_theme', $args['method'], $args['priority']);

            # Filter List Of Image Size
            $this->add_filter('intermediate_image_sizes', 'intermediate_image_sizes', $args['priority']);
        }

        public function setup_image_size()
        {
        }

        public function add_image_size($name, $width, $height, $crop = false)
        {
            add_image_size($name, $width, $height, $crop);
        }

        public function remove_image_size($name)
        {
            remove_image_size($name);
        }

        public function intermediate_image_sizes($sizes)
        {
            return $sizes;
        }
    }

}