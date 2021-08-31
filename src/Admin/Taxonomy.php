<?php

namespace WPTrait\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use WPTrait\Has\HasAdminFooter;
use WPTrait\Has\HasAdminInit;
use WPTrait\Has\HasAdvanceSearchBox;
use WPTrait\Has\HasBulkActions;
use WPTrait\Has\HasNotice;
use WPTrait\Has\HasRowActions;
use WPTrait\Has\HasSortableColumns;
use WPTrait\Has\HasTaxonomyColumns;
use WPTrait\Has\HasTerm;

if (!class_exists('Taxonomy')) {

    class Taxonomy extends Page
    {
        use HasTerm, HasNotice, HasBulkActions, HasRowActions, HasAdminInit, HasAdminFooter, HasTaxonomyColumns, HasSortableColumns;

        public $slug, $name, $post_types = array(), $args = array();

        public function __construct($slug, $name, $post_types = array(), $args = array())
        {
            // Define Taxonomy in WordPress
            $this->slug = $slug;
            $this->name = $name;
            $this->post_types = $post_types;
            $this->args = $args;

            // Register Taxonomy
            // @see https://developer.wordpress.org/reference/functions/register_taxonomy/
            add_action('init', array($this, 'register_taxonomy'));

            // Change Taxonomy Argument
            $this->add_filter('register_taxonomy_args', 'taxonomy_args', 10, 2);

            // Taxonomy Update Message
            $this->add_filter('term_updated_messages', 'term_updated_messages', 10, 1);

            // Register Admin Init
            $this->register_admin_init();

            // Register Admin Footer
            $this->register_admin_footer();

            // Register Admin Notice
            $this->register_notice();

            // Register Bulk Action
            $this->register_bulk_actions();

            // Register Row Action
            $this->register_row_actions($this->slug);

            // Register Taxonomy Columns
            $this->register_taxonomy_columns();
            $this->register_sortable_columns();
        }

        public function register_taxonomy()
        {
            $labels = array(
                'name' => $this->name,
                'singular_name' => $this->name,
                'menu_name' => $this->name
            );
            $default = array(
                'labels' => $labels,
                'hierarchical' => true,
                'public' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'show_in_nav_menus' => true,
                'show_tagcloud' => false,
                'rewrite' => array(
                    'slug' => $this->slug,
                    'with_front' => true
                )
            );
            $args = wp_parse_args($this->args, $default);
            register_taxonomy($this->slug, $this->post_types, $args);
        }

        public function taxonomy_args($args, $taxonomy)
        {
            // if($taxonomy == $this->slug) {}
            return $args;
        }

        public function term_updated_messages($message)
        {
            return $message;
        }

        public function in_page()
        {
            global $pagenow;
            return ($pagenow == "edit-tags.php" and isset($_GET['taxonomy']) and $_GET['taxonomy'] == $this->slug);
        }

        public function admin_url($args = array(), $paged = false, $search = false)
        {
            if ($paged) {
                $args = array_merge($args, array('paged' => (get_query_var('paged')) ? get_query_var('paged') : 1));
            }
            if ($search) {
                if (isset($_REQUEST['s'])) {
                    $args = array_merge($args, array('s' => trim($_REQUEST['s'])));
                }
                if (isset($_REQUEST[HasAdvanceSearchBox::$SearchTypeField])) {
                    $args = array_merge($args, array(HasAdvanceSearchBox::$SearchTypeField => trim($_REQUEST[HasAdvanceSearchBox::$SearchTypeField])));
                }
            }
            return add_query_arg(array_merge(array('taxonomy' => $this->slug), $args), 'edit-tags.php');
        }
    }

}