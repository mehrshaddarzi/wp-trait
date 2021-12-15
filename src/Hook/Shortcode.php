<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\Shortcode')) {

    trait Shortcode
    {

        public function bootShortcode($arg = [])
        {
            $defaults = [
                'method' => 'add_shortcode'
            ];
            $args = wp_parse_args($arg, $defaults);

            foreach ($this->search_methods($args['method']) as $method) {
                add_shortcode(str_ireplace($args['method'] . "_", "", $method), [$this, $method]);
            }
        }

        public function add_shortcode($atts, $content = null)
        {
        }

        public function get_shortcode_tags()
        {
            return (isset($GLOBALS['shortcode_tags']) ? $GLOBALS['shortcode_tags'] : []);
        }
    }

}
