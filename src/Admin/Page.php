<?php

namespace WPTrait\Admin;

use WPTrait\Information;
use WPTrait\Model;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Admin\Page')) {

    abstract class Page extends Model
    {

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

        abstract protected function in_page();

        abstract protected function admin_url();
    }

}