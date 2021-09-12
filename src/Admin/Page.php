<?php

namespace WPTrait\Admin;

use WPTrait\Model;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Page')) {

    abstract class Page extends Model
    {
        public $pagenow;

        public function __construct()
        {
            $this->pagenow = $GLOBALS['pagenow'];
        }

        abstract protected function in_page();

        abstract protected function admin_url();
    }

}