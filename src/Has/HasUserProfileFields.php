<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasUserProfileFields')) {

    trait HasUserProfileFields
    {

        public function register_user_profile_fields($priority = 10)
        {
            $this->add_action('show_user_profile', 'admin_user_profile_fields', $priority);
            $this->add_action('edit_user_profile', 'admin_user_profile_fields', $priority);
            $this->add_action('personal_options_update', 'save_admin_user_profile_fields', $priority);
            $this->add_action('edit_user_profile_update', 'save_admin_user_profile_fields', $priority);
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