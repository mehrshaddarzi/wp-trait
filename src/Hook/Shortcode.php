<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\Shortcode')) {

    trait Shortcode
    {

        public function bootShortcode($arg = array())
        {
            $defaults = array(
                'method' => 'add_shortcode',
                'priority' => 10,
            );
            $args = wp_parse_args($arg, $defaults);

            $this->add_action('add_shortcode', $args['method'], $args['priority'], 2);
        }

        public function add_shortcode($atts, $content = null)
        {
        }

        public function do_shortcode($content, $ignore_html = false)
        {
            return do_shortcode($content, $ignore_html);
        }

        public function remove_shortcode($name)
        {
            return remove_shortcode($name);
        }

        public function get_shortcode_tags()
        {
            return (isset($GLOBALS['shortcode_tags']) ? $GLOBALS['shortcode_tags'] : array());
        }
    }

}