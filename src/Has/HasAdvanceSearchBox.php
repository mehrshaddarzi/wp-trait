<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasAdvanceSearchBox')) {

    trait HasAdvanceSearchBox
    {
        use HasAdminFooter, HasPreGetQuery;

        public function register_search_box($type)
        {
            $this->register_pre_get_query($type);
        }

        public function admin_footer_search_box()
        {
            $search_input_id = $this->get_search_input_id();
            $search_fields = $this->get_search_fields();
            $current_value = '';
            if (isset($_REQUEST['s']) and !empty($_REQUEST['s'])) {
                $current_value = trim($_REQUEST['s']);
            }
            if (!is_null($search_input_id) and !empty($search_fields) and $this->in_page()) {
                include __DIR__ . '/../../templates/search-box.php';
            }
        }

        public function get_search_input_id()
        {
            global $post_type, $pagenow;

            // Post Type
            if (!empty($post_type)) {
                return 'post-search-input';
            }

            // Users Page
            if ($pagenow == "users.php") {
                return 'user-search-input';
            }

            // Taxonomy Page
            if ($pagenow == "edit-tags.php" and isset($_GET['taxonomy']) and !empty($_GET['taxonomy'])) {
                return 'tag-search-input';
            }

            return null;
        }

        public function get_search_fields()
        {
            /*$example = array(
                'order_id' => 'Order ID',
                'send_order_complete' => array(
                    'title' => 'Status',
                    'type' => 'select',
                    'choices' => array(
                        'no' => 'No',
                        'yes' => 'Yes',
                    )
                )
            );*/
            return array();
        }
    }

}