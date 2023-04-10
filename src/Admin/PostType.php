<?php

namespace WPTrait\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use WPTrait\Hook\
{
    AdminFooter,
    AdminInit,
    AdminSearchBox,
    BulkActions,
    Notice,
    PostTypeColumns,
    RowActions,
    SortableColumns,
    ViewsSub
};
use WPTrait\Information;

if (!class_exists('WPTrait\Admin\PostType')) {

    class PostType extends Page
    {
        use Notice, BulkActions, RowActions, AdminInit, AdminFooter, PostTypeColumns, SortableColumns, ViewsSub;

        /**
         * post type slug
         *
         * @var string
         */
        public $slug;

        /**
         * post type name
         *
         * @var string
         */
        public $name;

        /**
         * post type argument
         *
         * @var array
         */
        public $args = [];

        /**
         * Get Plugin Data
         *
         * @var Information
         */
        public Information $plugin;

        public function __construct(Information $plugin, $slug, $name, $args = [])
        {
            // Define Post Type in WordPress
            $this->plugin = $plugin;
            $this->slug = $slug;
            $this->name = $name;
            $this->args = $args;

            // Parent
            parent::__construct($plugin);

            // Register Post Type
            add_action('init', [$this, 'register_post_type']);

            // Change Post Type Argument
            $this->add_filter('register_post_type_args', 'post_type_args', 10, 2);

            // Post Type Update Message
            $this->add_filter('post_updated_messages', 'post_updated_messages');
        }

        public function __get($name)
        {
            return $this->$name;
        }

        public function register_post_type()
        {
            $labels = [
                'name' => $this->name,
                'singular_name' => $this->name,
                'menu_name' => $this->name,
                'name_admin_bar' => $this->name,
            ];
            $default = [
                'labels' => $labels,
                'description' => '',
                'public' => true,
                'publicly_queryable' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'has_archive' => false,
                'hierarchical' => false, // `action_rows` not work when is true
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'rewrite' => [
                    'slug' => $this->slug,
                    'with_front' => true
                ]
            ];
            $args = wp_parse_args($this->args, $default);
            register_post_type($this->slug, $args);
        }

        public function post_type_args($args, $post_type)
        {
            // if($post_type == $this->slug) {}
            return $args;
        }

        public function post_updated_messages($message)
        {
            return $message;
        }

        public function in_page()
        {
            return ($this->global->page_now == "edit.php" and isset($_GET['post_type']) and $_GET['post_type'] == $this->slug);
        }

        public function admin_url($args = [], $paged = false, $search = false)
        {
            if ($paged) {
                $args = array_merge($args, array('paged' => (get_query_var('paged')) ? get_query_var('paged') : 1));
            }
            if ($search) {
                if (isset($_REQUEST['s'])) {
                    $args = array_merge($args, array('s' => trim($_REQUEST['s'])));
                }
                if (isset($_REQUEST[AdminSearchBox::$SearchTypeField])) {
                    $args = array_merge($args, array(AdminSearchBox::$SearchTypeField => trim($_REQUEST[AdminSearchBox::$SearchTypeField])));
                }
            }
            return add_query_arg(array_merge(array('post_type' => $this->slug), $args), 'edit.php');
        }
    }

}
