<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('User')) {

    class User
    {

        /**
         * User ID
         *
         * @var int
         */
        public $user_id;

        /**
         * Meta Class
         */
        public $meta;

        public function __construct($user_id = null)
        {
            $this->user_id = $user_id;
            $this->meta = new Meta('user', $this->user_id);
        }

        public function get($user_id = null)
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
            return new \WP_User((is_null($user_id) ? $this->user_id : $user_id));
        }

        public function delete($reassign = null, $user_id = null)
        {
            return wp_delete_user((is_null($user_id) ? $this->user_id : $user_id), $reassign);
        }

        public function add($arg = array())
        {
            $default = array(
                'username' => '',
                'email' => '',
                'first_name' => '',
                'last_name' => '',
                'fullname' => '',
                'password' => ''
            );
            $args = wp_parse_args($arg, $default);

            # (int|WP_Error) The newly created user's ID or a WP_Error object if the user could not be created.
            return wp_insert_user($this->convertAliasArg($args));
        }

        public function update($arg = array())
        {
            $default = array(
                'id' => $this->user_id
            );
            $args = wp_parse_args($arg, $default);

            # (int|WP_Error) The post ID on success. The value 0 or WP_Error on failure.
            return wp_update_user($this->convertAliasArg($args));
        }

        public function exists($user_id = null)
        {
            $user = $this->get((is_null($user_id) ? $this->user_id : $user_id));
            return $user->exists();
        }

        public function list($arg = array())
        {
            # alias
            $alias = array(
                'return' => 'fields',
                'meta' => 'meta_query',
                'date' => 'date_query',
            );
            $arg = $this->convertAliasArg($arg, $alias);

            # Default
            $default = array(
                'role__in' => array(),
                'orderby' => 'id',
                'order' => 'ASC',
                'count_total' => false
            );
            $args = wp_parse_args($arg, $default);

            # Query
            $user_search = new \WP_User_Query($args);
            return (array)$user_search->get_results();
        }

        public function auth()
        {
            return is_user_logged_in();
        }

        public function current()
        {
            return wp_get_current_user();
        }

        public function id()
        {
            return get_current_user_id();
        }

        public function has_role($role, $user_id = null)
        {
            $user = $this->get((is_null($user_id) ? $this->user_id : $user_id));
            return in_array($role, (array)$user->roles);
        }

        public function user_can($cap, $user_id = null)
        {
            return user_can((is_null($user_id) ? $this->user_id : $user_id), $cap);
        }

        private function aliasArgument()
        {
            return array(
                'id' => 'ID',
                'pass' => 'user_pass',
                'password' => 'user_pass',
                'login' => 'user_login',
                'username' => 'user_login',
                'nicename' => 'user_nicename',
                'url' => 'user_url',
                'site' => 'user_url',
                'email' => 'user_email',
                'name' => 'display_name',
                'fullname' => 'display_name',
                'color' => 'admin_color',
                'date' => 'user_registered',
                'created_at' => 'user_registered',
                'admin_bar' => 'show_admin_bar_front',
                'ssl' => 'use_ssl'
            );
        }

        private function convertAliasArg($array = array(), $alias = null)
        {
            $_array = array();
            $alias = (is_null($alias) ? $this->aliasArgument() : $alias);
            foreach ($array as $key => $value) {
                $_array[(isset($alias[$key]) ? $alias[$key] : $key)] = $value;
            }

            return $_array;
        }
    }
}