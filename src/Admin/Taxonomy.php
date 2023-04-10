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
    SortableColumns,
    TaxonomyColumns
};
use WPTrait\Information;

if (!class_exists('WPTrait\Admin\Taxonomy')) {

    class Taxonomy extends Page
    {
        use Notice, BulkActions, AdminInit, AdminFooter, TaxonomyColumns, SortableColumns;

        /**
         * Get Plugin Data
         *
         * @var Information
         */
        public Information $plugin;

        public $slug, $name;
        public $post_types, $args = [];

        public function __construct(Information $plugin, $slug, $name, $post_types = [], $args = [])
        {
            // Define Taxonomy in WordPress
            $this->plugin = $plugin;
            $this->slug = $slug;
            $this->name = $name;
            $this->post_types = $post_types;
            $this->args = $args;

            // Parent
            parent::__construct($plugin);

            // Register Taxonomy
            add_action('init', [$this, 'register_taxonomy']);

            // Change Taxonomy Argument
            $this->add_filter('register_taxonomy_args', 'taxonomy_args', 10, 2);

            // Taxonomy Update Message
            $this->add_filter('term_updated_messages', 'term_updated_messages', 10, 1);
        }

        public function __get($name)
        {
            return $this->$name;
        }

        public function register_taxonomy()
        {
            $labels = [
                'name' => $this->name,
                'singular_name' => $this->name,
                'menu_name' => $this->name
            ];
            $default = [
                'labels' => $labels,
                'hierarchical' => true,
                'public' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'show_in_nav_menus' => true,
                'show_tagcloud' => false,
                'rewrite' => [
                    'slug' => $this->slug,
                    'with_front' => true
                ]
            ];
            $args = wp_parse_args($this->args, $default);
            register_taxonomy($this->slug, $this->post_types, $args);
        }

        public function taxonomy_args($args, $taxonomy)
        {
            # if($taxonomy == $this->slug) {}
            return $args;
        }

        public function term_updated_messages($message)
        {
            return $message;
        }

        public function in_page()
        {
            return ($this->global->page_now == "edit-tags.php" and isset($_GET['taxonomy']) and $_GET['taxonomy'] == $this->slug);
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
            return add_query_arg(array_merge(array('taxonomy' => $this->slug), $args), 'edit-tags.php');
        }
    }

}
