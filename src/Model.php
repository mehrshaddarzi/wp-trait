<?php

namespace WPTrait;

use WPTrait\Collection\Hooks;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Model')) {

    class Model
    {
        use Hooks;

        public $db, $wp, $plugin, $pagenow;

        public function __construct($plugin = array())
        {
            //@see https://codex.wordpress.org/Global_Variables
            $this->db = $GLOBALS['wpdb'];
            $this->wp = $GLOBALS['wp'];
            $this->pagenow = $GLOBALS['pagenow'];

            // Set Plugin information
            $this->plugin = $plugin;

            // Boot WordPress Hooks
            $this->bootHooks();
        }

        public function bootHooks()
        {
            $booted = array();
            $Trait = (array)array_keys(class_uses($this));
            foreach ($Trait as $trait) {
                $basename = basename(str_replace('\\', '/', $trait));
                $method = 'boot' . $basename;
                $args = array();
                if (method_exists($this, $method) && !in_array($method, $booted)) {
                    $booted[] = $method;
                    $variable = lcfirst($basename);
                    $this->{$method}((isset($this->{$variable}) ? $this->{$variable} : $args));
                }
            }
        }

        private function getFile($path = '', $type = 'url')
        {
            return rtrim($this->plugin->{$type}, '/') . '/' . ltrim($path, '/');
        }

        public function getFileUrl($path = '')
        {
            return $this->getFile($path, 'url');
        }

        public function getFilePath($path = '')
        {
            return $this->getFile($path, 'path');
        }

    }

}
