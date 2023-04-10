<?php

namespace WPTrait\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use WPTrait\Hook\
{
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
use WPTrait\Information;

if (!class_exists('WPTrait\Admin\Users')) {

    class Users extends Page
    {
        use Notice, AdminAssets, BulkActions, RowActions, AdminInit, AdminFooter, SortableColumns, UserProfileFields, UserColumns;

        public $rowActions = ['type' => 'user'];

        /**
         * Get Plugin Data
         *
         * @var Information
         */
        public Information $plugin;

        public function __construct(Information $plugin)
        {
            parent::__construct($plugin);
        }

        public function in_page()
        {
            return ($this->global->page_now == "users.php");
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
            return add_query_arg($args, 'users.php');
        }
    }

}