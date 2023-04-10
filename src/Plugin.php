<?php

namespace WPTrait;

use WPTrait\Collection\Hooks;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Plugin')) {

    abstract class Plugin
    {
        use Hooks;

        /**
         * Get Plugin Data
         *
         * @var Information
         */
        public Information $plugin;

        public function __construct($slug, $args = [])
        {

            // Setup Plugin Data
            $this->plugin = new Information($slug, $args);

            // include PHP files
            $this->includes();

            // setup WordPress hooks
            $this->init_hooks();

            // Instantiate Object Class
            add_action($this->plugin->when_load->action, [$this, 'instantiate'], $this->plugin->when_load->priority);

            // Set global function
            if (!empty($this->plugin->function)) {
                $this->setup_global_function();
            }

            // Plugin Loaded Action
            do_action($this->plugin->prefix . '_loaded');
        }

        public function includes()
        {
        }

        abstract function instantiate();

        public function init_hooks()
        {
            // Load Text Domain
            if (isset($this->plugin->textDomain) and !empty($this->plugin->textDomain)) {
                load_plugin_textdomain($this->plugin->textDomain, false, wp_normalize_path($this->plugin->path . '/languages'));
            }

            // register_activation_hook
            if (method_exists($this, 'register_activation_hook')) {
                register_activation_hook($this->plugin->mainFile, [$this, 'register_activation_hook']);
            }

            // register_deactivation_hook
            if (method_exists($this, 'register_deactivation_hook')) {
                register_deactivation_hook($this->plugin->mainFile, [$this, 'register_deactivation_hook']);
            }

            // register_uninstall_hook
            if (method_exists($this, 'register_uninstall_hook')) {
                register_uninstall_hook($this->plugin->mainFile, [__CLASS__, 'register_uninstall_hook']);
            }
        }

        public function register_activation_hook()
        {
        }

        public function register_deactivation_hook()
        {
        }

        public static function register_uninstall_hook()
        {
        }

        private function setup_global_function()
        {
            $GLOBALS[$this->plugin->function] = $this;

            // Create global function for backwards compatibility.
            $function = 'if(!function_exists(\'' . $this->plugin->function . '\')) { function ' . $this->plugin->function . '() { return $GLOBALS[\'' . $this->plugin->function . '\']; }}';
            eval($function);
        }
    }

}
