<?php

namespace WPTrait\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use WPTrait\Hook\{
    AdminAssets,
    AdminFooter,
    AdminInit,
    BulkActions,
    Notice,
    RowActions,
    SortableColumns,
    UserColumns,
    UserProfileFields,
    AdminSearchBox
};

if (!class_exists('Users')) {

    class Users extends Page
    {
        use Notice, AdminAssets, BulkActions, RowActions, AdminInit, AdminFooter, SortableColumns, UserProfileFields, UserColumns;

        public $rowActions = array('type' => 'user');

        public function __construct($plugin = array())
        {
            parent::__construct($plugin);
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