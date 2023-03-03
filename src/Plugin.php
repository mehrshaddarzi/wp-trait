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
        public $plugin;

        public function __construct($slug, $args = [])
        {

            // Setup Plugin Data
            $this->plugin = new Information($slug, $args);

            // include PHP files
            $this->includes();

            // setup WordPress hook
            $this->init_hooks();

            // Set Global Function
            if (!empty($arg['global'])) {
                $GLOBALS[$arg['global']] = $this;

                // Create global function for backwards compatibility.
                $function = 'if(!function_exists(\'' . $arg['global'] . '\')) { function ' . $arg['global'] . '() { return $GLOBALS[\'' . $arg['global'] . '\']; }}';
                eval($function);
            }

            // Instantiate Object Class
            add_action($arg['when_load']['action'], [$this, 'instantiate'], $arg['when_load']['priority']);

            // Plugin Loaded Action
            do_action($this->plugin->prefix . '_loaded');
        }

        public function includes()
        {
        }

        public function instantiate()
        {
        }

        public function init_hooks()
        {
            // Load Text Domain
            if (isset($this->plugin->textDomain) and !empty($this->plugin->textDomain)) {
                load_plugin_textdomain($this->plugin->textdomain, false, wp_normalize_path($this->plugin->path . '/languages'));
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
    }

}
