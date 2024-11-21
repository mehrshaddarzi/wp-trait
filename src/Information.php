<?php

namespace WPTrait;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Information')) {

    class Information
    {

        /**
         * Plugin Slug
         *
         * @var string
         */
        public $slug;

        /**
         * Plugin Main PHP file path
         *
         * @var string
         */
        public $mainFile;

        /**
         * Plugin prefix
         *
         * @var string
         */
        public $prefix;

        /**
         * Plugin Base Url
         *
         * @var string
         */
        public $url;

        /**
         * Plugin Base Path
         *
         * @var string
         */
        public $path;

        /**
         * Plugin Name
         *
         * @var string
         */
        public $name;

        /**
         * Plugin Text Domain
         *
         * @var string
         */
        public $textDomain;

        /**
         * Plugin Version
         *
         * @var string
         */
        public $version;

        /**
         * Plugin Description
         *
         * @var string
         */
        public $description;

        /**
         * Plugin Author Name
         *
         * @var string
         */
        public $author;

        /**
         * Minimum required version of WordPress.
         *
         * @var string
         */
        public $requiresWP;

        /**
         * Minimum required version of PHP.
         *
         * @var string
         */
        public $requiresPHP;

        /**
         * Whether the plugin can only be activated network-wide.
         *
         * @var boolean
         */
        public $network;

        /**
         * Get plugin data
         *
         * @var object
         */
        public $data;

        /**
         * Get WordPress action name for load this plugin
         *
         * @var \Trait_Plugin_When_Load_meta
         */
        public $when_load;

        /**
         * Get Global function name for this plugin
         *
         * @var string
         */
        public $function;

        public function __construct($slug, $args = [])
        {
            // Set Plugin Slug
            $this->slug = $slug;

            // Setup Argument
            $default = [
                'main_file' => (new Constant())->plugin_dir . '/' . $slug . '/' . $slug . '.php',
                'global' => $this->sanitize_plugin_slug($slug),
                'prefix' => $this->sanitize_plugin_slug($slug),
                'when_load' => ['action' => 'plugins_loaded', 'priority' => 10]
            ];
            $arg = wp_parse_args($args, $default);

            // Set Main File
            $this->mainFile = $arg['main_file'];

            // Set Prefix
            $this->prefix = $arg['prefix'];

            // Set When Load
            $this->when_load = (object)$arg['when_load'];

            // Set global function
            $this->function = $arg['global'];

            // Define Variable
            $this->get_plugin_data();
        }

        private function get_plugin_data()
        {
            if (!function_exists('get_plugin_data')) {
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }

            // Setup Plugin Data
            $this->data = (object)get_plugin_data($this->mainFile, true, false);

            // Set Url
            $this->url = plugins_url('', $this->mainFile);

            // Set Path
            $this->path = plugin_dir_path($this->mainFile);

            // Set TextDomain
            $this->textDomain = $this->data->TextDomain;

            // Set Version
            $this->version = $this->data->Version;

            // Set Description
            $this->description = $this->data->Description;

            // Set Author Name
            $this->author = $this->data->Author;

            // Set Minimum required version of WordPress
            $this->requiresWP = $this->data->RequiresWP;

            // Set Minimum required version of PHP
            $this->requiresPHP = $this->data->RequiresPHP;

            // Set Network
            $this->network = (bool)$this->data->Network;
        }

        protected function sanitize_plugin_slug($slug)
        {
            return str_replace("-", "_", trim($slug));
        }

        public function get_data(): object
        {
            return $this->data;
        }

        public function url($path): string
        {
            return rtrim($this->url, "/") . "/" . ltrim($path, "/");
        }

        public function path($path): string
        {
            return rtrim($this->path, "/") . "/" . ltrim($path, "/");
        }
    }

}
