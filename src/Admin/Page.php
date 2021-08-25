<?php

namespace WPTrait\Admin;

use WPTrait\Has\HasHooks;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Page')) {

    abstract class Page
    {
        use HasHooks;

        abstract protected function in_page();
        abstract protected function admin_url();
    }

}