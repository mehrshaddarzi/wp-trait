<?php

namespace WPTrait;

use WPTrait\Has\HasHooks;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Model')) {

    class Model
    {
        use HasHooks;

        public $db, $wp;

        public function __construct()
        {
            //@see https://codex.wordpress.org/Global_Variables
            $this->db = $GLOBALS['wpdb'];
            $this->wp = $GLOBALS['wp'];
        }
    }

}
