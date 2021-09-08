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

        public function get_post($post_id, $output = OBJECT)
        {
            return get_post($post_id, $output);
        }

        public function get_post_collection($post_id, $meta = array(), $taxonomy = array())
        {
            $post_object = $this->get_post($post_id, OBJECT);
            if (is_null($post_object)) {
                return null;
            }

            // Check Meta
            if ($meta == "all") {
                $post_object->meta = (object)$this->get_post_meta($post_id);
            } elseif (is_array($meta) and !empty($meta)) {
                foreach ($meta as $meta_key) {
                    $post_object->meta->{$meta_key} = $this->get_post_meta($post_id, $meta_key, true);
                }
            }

            // Check Taxonomy
            if (!empty($taxonomy)) {
                foreach ($taxonomy as $tax) {
                    if (taxonomy_exists($tax)) {
                        $post_object->{$tax} = $this->get_post_terms($post_id, $tax);
                    }
                }
            }

            return $post_object;
        }

        public function get_post_permalink($post_id, $leave_name = false)
        {
            return get_the_permalink($post_id, $leave_name);
        }

        public function get_post_thumbnail_id($post_id)
        {
            return get_post_thumbnail_id($post_id);
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

        public function get_post_meta($post_id, $meta_key = '', $single = false)
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

        public function get_post_terms($post_id, $taxonomy = 'post_tag', $args = array('fields' => 'all'))
        {
            return wp_get_post_terms($post_id, $taxonomy, $args);
        }

        public function post_exists($post_id)
        {
            return is_string(get_post_status($post_id));
        }

        public function get_post_types($args = array(), $output = 'objects', $operator = 'and')
        {
            return get_post_types($args, $output, $operator);
        }

        public function in_edit_page($new_edit = null)
        {
            # global $typenow; (is_edit_page('new') and $typenow =="POST_TYPE")
            global $pagenow;
            if (!is_admin()) return false;
            if ($new_edit == "edit")
                return in_array($pagenow, array('post.php',));
            elseif ($new_edit == "new")
                return in_array($pagenow, array('post-new.php'));
            else
                return in_array($pagenow, array('post.php', 'post-new.php'));
        }
    }

}