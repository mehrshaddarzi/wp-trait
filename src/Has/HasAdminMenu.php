<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasAdminMenu')) {

    trait HasAdminMenu
    {

        public function register_admin_menu()
        {
            $this->add_action('admin_menu', 'admin_menu');
        }

        public function admin_menu()
        {
            global $menu, $submenu;
        }

        public function add_menu($page_title = '', $menu_title = '', $capability = 'manage_options', $menu_slug = '', $function = '', $icon_url = '', $position = null)
        {
            add_menu_page($page_title, ($menu_title == "" ? $page_title : $menu_title), $capability, $menu_slug, $function, $icon_url, $position);
        }

        public function add_submenu($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '', $position = null)
        {
            add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function, $position);
        }
    }

}