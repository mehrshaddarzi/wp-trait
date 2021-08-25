<?php

namespace WPTrait\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use WPTrait\Has\HasAdminInit;
use WPTrait\Has\HasBulkActions;
use WPTrait\Has\HasNotice;
use WPTrait\Has\HasPost;
use WPTrait\Has\HasPostTypeColumns;
use WPTrait\Has\HasRowActions;
use WPTrait\Has\HasSortableColumns;
use WPTrait\Has\HasSubSub;

if (!class_exists('PostType')) {

    class PostType extends Page
    {
        use HasPost, HasNotice, HasBulkActions, HasRowActions, HasAdminInit, HasPostTypeColumns, HasSortableColumns, HasSubSub;

        public $slug, $name, $args = array();

        public function __construct($slug, $name, $args = array())
        {
            // Define Post Type in WordPress
            $this->slug = $slug;
            $this->name = $name;
            $this->args = $args;

            // Register Post Type
            // @see https://developer.wordpress.org/reference/functions/register_post_type/
            add_action('init', array($this, 'register_post_type'));

            // Change Post Type Argument
            $this->add_filter('register_post_type_args', 'post_type_args', 10, 2);

            // Post Type Update Message
            // @see https://developer.wordpress.org/reference/hooks/post_updated_messages/
            $this->add_filter('post_updated_messages', 'post_updated_messages', 10, 1);

            // Register Admin Init
            $this->register_admin_init();

            // Register Admin Notice
            $this->register_notice();

            // Register Bulk Action
            $this->register_bulk_actions();

            // Register Row Action
            $this->register_row_actions('post');

            // Register Post Type Columns
            $this->register_post_type_columns();
            $this->register_sortable_columns();

            // Register Views Sub Sub
            $this->register_views_sub();
        }

        public function register_post_type()
        {
            $labels = array(
                'name' => $this->name,
                'singular_name' => $this->name,
                'menu_name' => $this->name,
                'name_admin_bar' => $this->name,
            );
            $default = array(
                'labels' => $labels,
                'description' => '',
                'public' => true,
                'publicly_queryable' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'has_archive' => false,
                'hierarchical' => true,
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'rewrite' => array(
                    'slug' => $this->slug,
                    'with_front' => true
                )
            );
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
            global $pagenow;
            return ($pagenow == "edit.php" and isset($_GET['post_type']) and $_GET['post_type'] == $this->slug);
        }

        public function admin_url($args = array())
        {
            return add_query_arg(array_merge(array('post_type' => $this->slug), $args), 'edit.php');
        }
    }

}