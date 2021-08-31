<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasPost')) {

    trait HasPost
    {
        public $slug;

        public function get_posts($arg = array())
        {
            $default = array(
                'post_type' => $this->slug,
                'post_status' => 'publish',
                'posts_per_page' => '-1',
                'order' => 'DESC',
                'fields' => 'ids',
                'cache_results' => false,
                'no_found_rows' => true, //@see https://10up.github.io/Engineering-Best-Practices/php/#performance
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'suppress_filters' => true
                /**
                 * @see https://developer.wordpress.org/reference/classes/wp_meta_query/#accepted-arguments
                 * 'meta_query' => array(
                 * array(
                 * 'key' => '',
                 * 'value' => '',
                 * 'compare' => '='
                 * )
                 * ),
                 */
            );
            $args = wp_parse_args($arg, $default);

            // Return { $query->posts }
            return new \WP_Query($args);
        }

        public function get_post($post_id, $output = ARRAY_A)
        {
            return get_post($post_id, $output);
        }

        public function delete_post($post_id, $force = false)
        {
            //(WP_Post|false|null) Post data on success, false or null on failure.
            return wp_delete_post($post_id, $force);
        }

        public function add_post($arg = array())
        {
            $default = array(
                'post_title' => '',
                'post_date' => current_time('mysql'),
                'post_excerpt' => '',
                'post_type' => $this->slug,
                'post_status' => 'publish',
                'post_content' => '',
                'post_author' => get_current_user_id(),
            );
            $args = wp_parse_args($arg, $default);

            // (int|WP_Error) The post ID on success. The value 0 or WP_Error on failure.
            return wp_insert_post(
                $args
            );
        }

        public function update_post($post_id, $arg = array())
        {
            $default = array(
                'ID' => $post_id
            );
            $args = wp_parse_args($arg, $default);

            // (int|WP_Error) The post ID on success. The value 0 or WP_Error on failure.
            return wp_update_post($args);
        }

        public function get_post_meta($post_id, $meta_key = '', $single = true)
        {
            if (!$single) {
                return array_map(function ($a) {
                    return $a[0];
                }, get_post_meta($post_id));
            }

            return get_post_meta($post_id, $meta_key, $single);
        }

        public function update_post_meta($post_id, $meta_key, $new_value)
        {
            update_post_meta($post_id, $meta_key, $new_value);
        }

        public function add_post_meta($post_id, $meta_key, $new_value)
        {
            add_post_meta($post_id, $meta_key, $new_value);
        }

        public function delete_post_meta($post_id, $meta_key)
        {
            return delete_post_meta($post_id, $meta_key);
        }

        public function post_exists($post_id)
        {
            return is_string(get_post_status($post_id));
        }

        public function get_post_types($args = array(), $output = 'objects', $operator = 'and')
        {
            return get_post_types($args, $output, $operator);
        }
    }

}