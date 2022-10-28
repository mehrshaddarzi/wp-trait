<?php

namespace WPTrait\Collection;

use WPTrait\Utils\Arr;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\User')) {

    class User
    {

        /**
         * User ID
         *
         * @var int
         */
        public $user_id = null;

        /**
         * Meta Class
         */
        public $meta;

        /**
         * Alias argument for insert/update User
         */
        public $alias = [
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
            'registered' => 'user_registered',
            'admin_bar' => 'show_admin_bar_front',
            'activation_key' => 'user_activation_key',
            'status' => 'user_status',
            'ssl' => 'use_ssl'
        ];

        /**
         * Password Utility Class
         */
        public $password;

        public function __construct($user_id = null)
        {
            $this->user_id = (is_null($user_id) ? get_current_user_id() : $user_id);
            $this->meta = new Meta('user', $this->user_id);
            $this->password = new Password($this->user_id);
        }

        public function __get($property)
        {
            $arg = Arr::alias(array_combine($property, $property), $this->alias);
            if (!in_array($property, ['user_id', 'meta', 'password'])) {
                $user_data = $this->get($this->user_id);
                return $user_data->{array_keys($arg)[0]} ?? $this->$property;
            }

            return $this->$property;
        }

        public function get($user_id = null)
        {
            /**
             * @see https://core.trac.wordpress.org/browser/tags/5.8/src/wp-includes/class-wp-user.php
             * List Of Methods:
             *
             * add_role
             * remove_role
             * set_role
             * add_cap
             * remove_cap
             * remove_all_caps
             * has_cap
             * exists
             *
             */
            return new \WP_User((is_null($user_id) ? $this->user_id : $user_id));
        }

        public function delete($reassign = null, $user_id = null)
        {
            return wp_delete_user((is_null($user_id) ? $this->user_id : $user_id), $reassign);
        }

        public function add($arg = [])
        {
            $default = [
                'username' => '',
                'email' => '',
                'first_name' => '',
                'last_name' => '',
                'fullname' => '',
                'password' => ''
            ];
            $args = wp_parse_args($arg, $default);

            # (int|WP_Error) The newly created user's ID or a WP_Error object if the user could not be created.
            return wp_insert_user(Arr::alias($args, $this->alias));
        }

        public function update($arg = [])
        {
            $default = [
                'id' => $this->user_id
            ];
            $args = wp_parse_args($arg, $default);

            # (int|WP_Error) The post ID on success. The value 0 or WP_Error on failure.
            return wp_update_user(Arr::alias($args, $this->alias));
        }

        public function exists($value, $type = null)
        {
            # The field to query against: 'id', 'ID', 'slug', 'email' or 'login' or username.
            if (!is_null($type)) {
                $user = get_user_by(('username' == $type ? 'login' : $type), $value);
                if ($user) {
                    return $user->ID;
                }
                return false;
            }

            if (is_numeric($value)) {
                $user = $this->get($value);
                return ($user->exists() ? $value : false);
            }

            if (is_email($value)) {
                return email_exists($value);
            }

            return username_exists($value);
        }

        public function query($arg = [])
        {
            # alias
            $alias = [
                'return' => 'fields',
                'meta' => 'meta_query',
                'date' => 'date_query',
            ];
            $arg = Arr::alias($arg, $alias);

            # Check Return only ids
            if (isset($arg['fields']) and in_array($arg['fields'], ['id', 'ids', 'ID'])) {
                $arg['fields'] = ['ID'];
            }

            # Sanitize Meta Query
            if (isset($arg['meta_query']) and !isset($arg['meta_query'][0])) {
                $arg['meta_query'] = [$arg['meta_query']];
            }

            # Default
            $default = [
                'role__in' => [],
                'orderby' => 'id',
                'order' => 'ASC',
                'count_total' => false
            ];
            $args = wp_parse_args($arg, $default);

            # Query
            return new \WP_User_Query($args);
        }

        public function list($arg = [])
        {
            $query = $this->query($arg);
            $users = (array)$query->get_results();
            if (isset($users[0]) and count((array)$users[0]) == 1) {
                return array_column($users, key((array)$users[0]));
            }
            return $users;
        }

        public function toSql($arg = [])
        {
            return $this->query($arg)->request;
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

        public function can($cap, $user_id = null)
        {
            return user_can((is_null($user_id) ? $this->user_id : $user_id), $cap);
        }

        public function authenticate($username, $password)
        {
            return wp_authenticate($username, $password);
        }

        public function login($username, $password, $remember = false, $secure_cookie = '')
        {
            return wp_signon(['user_login' => $username, 'user_password' => $password, 'remember' => $remember], $secure_cookie);
        }
        
         public function authById( $user_id ) {
            wp_clear_auth_cookie();
            wp_set_current_user( $user_id );
            wp_set_auth_cookie( $user_id, true );
        
            // todo for Update session !? and wp_set_auth_cookie in Cookie Class.
           
        }
        public function logout()
        {
            wp_logout();
        }
    }
}
