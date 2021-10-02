<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\UserProfileFields')) {

    trait UserProfileFields
    {

        public function bootUserProfileFields($arg = [])
        {
            $defaults = [
                'method' => 'admin_user_profile_fields',
                'save_method' => 'save_admin_user_profile_fields',
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            $this->add_action('show_user_profile', $args['method'], $args['priority']);
            $this->add_action('edit_user_profile', $args['method'], $args['priority']);
            $this->add_action('personal_options_update', $args['save_method'], $args['priority']);
            $this->add_action('edit_user_profile_update', $args['save_method'], $args['priority']);
        }

        public function admin_user_profile_fields($user)
        {
            //@see https://developer.wordpress.org/reference/hooks/show_user_profile/
        }

        public function save_admin_user_profile_fields($user_id)
        {
            if (!current_user_can('edit_user', $user_id)) {
                return;
            }
        }

    }

}