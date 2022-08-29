<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\AdminMenu')) {

    trait AdminMenu
    {

        public function bootAdminMenu($arg = [])
        {
            $defaults = [
                'method' => 'admin_menu',
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            $this->add_action('admin_menu', $args['method'], $args['priority']);
        }

        public function admin_menu()
        {
            global $menu, $submenu;
        }

        public function add_menu($page_title = '', $menu_title = '', $capability = 'manage_options', $menu_slug = '', $function = '', $icon_url = '', $position = null, $submenus = [])
        {
            add_menu_page($page_title, ($menu_title == "" ? $page_title : $menu_title), $capability, $menu_slug, $function, $icon_url, $position);
            if (!empty($submenus) and is_array($submenus) and count($submenus) > 0) {
                foreach ($submenus as $submenu) {
                    $this->add_submenu(($submenu['parent_slug'] ?? $menu_slug), $submenu['page_title'], ($submenu['menu_title'] == "" ? $submenu['page_title'] : $submenu['menu_title']), $submenu['capability'], $submenu['menu_slug'], $submenu['function'], ($submenu['position'] ?? null));
                }
            }
        }

        public function add_submenu($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '', $position = null)
        {
            add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function, $position);
        }
    }

}
