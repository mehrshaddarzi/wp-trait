<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('User')) {

    trait User
    {

        public function get_users($arg = array())
        {
            $default = array(
                'role__in' => array(),
                'fields' => array('id'),
                'orderby' => 'id',
                'order' => 'ASC',
                'count_total' => false
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

            // Return { (array) $query->get_results() }
            // Get User Ids { $user_ids[0]->id }
            return new \WP_User_Query($args);
        }

        public function get_user($user_id)
        {
            /**
             * @see https://core.trac.wordpress.org/browser/tags/5.8/src/wp-includes/class-wp-user.php
             * List Of Methods:
             *
             * exists
             * add_role
             * remove_role
             * set_role
             * add_cap
             * remove_cap
             * remove_all_caps
             * has_cap
             *
             * @return object { 'data' => '', 'ID' => '', 'roles' => '', 'allcaps' => ''}
             */
            return new \WP_User($user_id);
        }

        public function delete_user($user_id, $reassign = null)
        {
            return wp_delete_user($user_id, $reassign);
        }

        public function add_user($arg = array())
        {
            $default = array(
                'user_login' => '',
                'user_email' => '',
                'first_name' => '',
                'last_name' => '',
                'display_name' => '',
                'user_pass' => ''
            );
            $args = wp_parse_args($arg, $default);

            // (int|WP_Error) The newly created user's ID or a WP_Error object if the user could not be created.
            return wp_insert_user(
                $args
            );
        }

        public function update_user($user_id, $arg = array())
        {
            $default = array(
                'ID' => $user_id,
                'first_name' => '',
                'last_name' => '',
            );
            $args = wp_parse_args($arg, $default);

            // (int|WP_Error) The post ID on success. The value 0 or WP_Error on failure.
            return wp_update_user($args);
        }

        public function get_user_meta($user_id, $meta_key, $single = false)
        {
            if (!$single) {
                return array_map(function ($a) {
                    return $a[0];
                }, get_user_meta($user_id));
            }

            return get_user_meta($user_id, $meta_key, $single);
        }

        public function update_user_meta($post_id, $meta_key, $new_value)
        {
            update_user_meta($post_id, $meta_key, $new_value);
        }

        public function add_user_meta($user_id, $meta_key, $new_value)
        {
            add_user_meta($user_id, $meta_key, $new_value);
        }

        public function delete_user_meta($user_id, $meta_key)
        {
            return delete_user_meta($user_id, $meta_key);
        }

        public function user_exists($user_id)
        {
            $user = $this->get_user($user_id);
            return $user->exists();
        }

        public function is_login()
        {
            return is_user_logged_in();
        }

        public function current_user_id()
        {
            return get_current_user_id();
        }

        public function has_role($user_id, $role)
        {
            $user = $this->get_user($user_id);
            return in_array($role, (array)$user->roles);
        }

        public function user_can($user_id, $cap)
        {
            return user_can($user_id, $cap);
        }
    }
}