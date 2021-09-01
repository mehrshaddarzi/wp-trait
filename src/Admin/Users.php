<?php

namespace WPTrait\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use WPTrait\Has\HasAdminAssets;
use WPTrait\Has\HasAdminFooter;
use WPTrait\Has\HasAdminInit;
use WPTrait\Has\HasAdvanceSearchBox;
use WPTrait\Has\HasBulkActions;
use WPTrait\Has\HasNotice;
use WPTrait\Has\HasRowActions;
use WPTrait\Has\HasSortableColumns;
use WPTrait\Has\HasUser;
use WPTrait\Has\HasUserColumns;
use WPTrait\Has\HasUserProfileFields;

if (!class_exists('Users')) {

    class Users extends Page
    {
        use HasUser, HasNotice, HasAdminAssets, HasBulkActions, HasRowActions, HasAdminInit, HasAdminFooter, HasSortableColumns, HasUserProfileFields, HasUserColumns;

        public function __construct()
        {

            // Register Admin Init
            $this->register_admin_init();

            // Register Admin Footer
            $this->register_admin_footer();

            // Register Admin Notice
            $this->register_admin_notices();

            // Register Bulk Action
            $this->register_bulk_actions();

            // Register Row Action
            $this->register_row_actions('user');

            // Register User Profile Field
            $this->register_user_profile_fields();

            // Register Admin Asset
            $this->register_admin_assets();

            // Register User columns
            $this->register_user_columns();
        }

        public function in_page()
        {
            global $pagenow;
            return ($pagenow == "users.php");
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
            return add_query_arg($args, 'users.php');
        }
    }

}