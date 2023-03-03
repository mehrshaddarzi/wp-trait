<?php

namespace WPTrait;

if (!class_exists('WPTrait\Globals')) {

    class Globals
    {

        /**
         * The global instance of the Class_Reference/WP class
         *
         * @var \WP
         */
        public $wp;

        /**
         * Get Current PHP File name in WordPress Admin area
         *
         * @var string
         */
        public $page_now;

        /**
         * Get Current PHP File name in WordPress Admin area
         *
         * @var \WP_Admin_Bar
         */
        public $admin_bar;

        /**
         * Get Current Screen
         *
         * @var \WP_Screen
         */
        public $screen;

        /**
         * Global WordPress Query
         *
         * @var \WP_Query
         */
        public $query;

        /**
         * Global WordPress Post data
         *
         * @var \WP_Post
         */
        public $post;

        /**
         * Get WordPress Version
         *
         * @var string
         */
        public $version;

        /**
         * Get WordPress db Version
         *
         * @var string
         */
        public $db_version;

        /**
         * The global instance of the Class_Reference/WP_Rewrite class
         *
         * @var \WP_Rewrite
         */
        public $rewrite;

        /**
         * WordPress Locale data
         *
         * @var object
         */
        public $locale;

        /**
         * WordPress User Roles
         *
         * @var \WP_Roles
         */
        public $roles;

        /**
         * WordPress Meta Boxes
         *
         * @var object
         */
        public $meta_boxes;

        /**
         * WordPress admin menu list
         *
         * @var array
         */
        public $menu;

        /**
         * WordPress admin sub menu list
         *
         * @var array
         */
        public $submenu;

        /**
         * WordPress registered Sidebars
         *
         * @var array
         */
        public $sidebars;

        /**
         * WordPress registered Widgets
         *
         * @var array
         */
        public $widgets;

        public function __construct()
        {
            global $wp, $pagenow, $wp_admin_bar, $current_screen, $wp_query, $post, $wp_version, $wp_db_version,
                   $wp_rewrite, $wp_locale, $wp_roles, $wp_meta_boxes, $menu, $submenu, $wp_registered_sidebars, $wp_registered_widgets;

            $this->version = $wp_version;
            $this->db_version = $wp_db_version;
            $this->rewrite = $wp_rewrite;
            $this->locale = $wp_locale;
            $this->roles = $wp_roles;
            $this->meta_boxes = $wp_meta_boxes;
            $this->sidebars = $wp_registered_sidebars;
            $this->menu = $menu;
            $this->submenu = $submenu;
            $this->widgets = $wp_registered_widgets;
            $this->wp = $wp;
            $this->page_now = $pagenow;
            $this->admin_bar = $wp_admin_bar;
            $this->query = $wp_query;
            $this->post = $post;
            $this->screen = $current_screen;
        }

        public function __get($name)
        {
            if (isset($this->{$name})) {
                return $this->{$name};
            }

            return (array_key_exists($name, $GLOBALS) ? $GLOBALS[$name] : null);
        }
    }

}
