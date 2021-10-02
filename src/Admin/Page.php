<?php

namespace WPTrait\Admin;

use WPTrait\Model;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Admin\Page')) {

    abstract class Page extends Model
    {

        public function __construct($plugin = [])
        {
            parent::__construct($plugin);
        }

        abstract protected function in_page();

        abstract protected function admin_url();
    }

}