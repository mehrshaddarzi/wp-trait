<?php

namespace WPTrait;

use WPTrait\Collection\Hooks;
use WPTrait\Hook\Constant;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Plugin')) {

    abstract class Plugin
    {
        use Hooks, Constant;

        public $plugin;

        public function __construct($slug, $args = array())
        {
            // Set Plugin Slug
            $this->plugin = new \stdClass();
            $this->plugin->slug = $slug;

            // Check Custom argument
            $default = array(
                'main_file' => $this->constant('plugin_dir') . '/' . $slug . '/' . $slug . '.php',
                'global' => $this->sanitize_plugin_slug($slug),
                'prefix' => $this->sanitize_plugin_slug($slug),
                'when_load' => array('action' => 'plugins_loaded', 'priority' => 10)
            );
            $arg = wp_parse_args($args, $default);

            // Set Main File
            $this->plugin->main_file = $arg['main_file'];

            // Set Prefix
            $this->plugin->prefix = $arg['prefix'];

            // Define Variable
            $this->define_constants();

            // include PHP files
            $this->includes();

            // init Wordpress hook
            $this->init_hooks();

            // Set Global Function
            if (!empty($arg['global']) and !is_null($arg['global'])) {
                $GLOBALS[$arg['global']] = $this;

                // Create global function for backwards compatibility.
                $function = 'function ' . $arg['global'] . '() { return $GLOBALS[\'' . $arg['global'] . '\']; }';
                eval($function);
            }

            // Instantiate Object Class
            add_action($arg['when_load']['action'], array($this, 'instantiate'), $arg['when_load']['priority']);

            // Plugin Loaded Action
            do_action($this->plugin->prefix . '_loaded');
        }

        public function __get($name)
        {
            return $this->$name;
        }

        public function define_constants()
        {
            if (!function_exists('get_plugin_data')) {
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }

            $this->plugin = (object)array_merge((array)$this->plugin, (array)array_change_key_case(get_plugin_data($this->plugin->main_file), CASE_LOWER));
            $this->plugin->url = plugins_url('', $this->plugin->main_file);
            $this->plugin->path = plugin_dir_path($this->plugin->main_file);
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
            if (isset($this->plugin->textdomain) and !empty($this->plugin->textdomain)) {
                load_plugin_textdomain($this->plugin->textdomain, false, wp_normalize_path($this->plugin->path . '/languages'));
            }

            // register_activation_hook
            if (method_exists($this, 'register_activation_hook')) {
                register_activation_hook($this->plugin->main_file, array($this, 'register_activation_hook'));
            }

            // register_deactivation_hook
            if (method_exists($this, 'register_deactivation_hook')) {
                register_deactivation_hook($this->plugin->main_file, array($this, 'register_deactivation_hook'));
            }

            // register_uninstall_hook
            if (method_exists($this, 'register_uninstall_hook')) {
                register_uninstall_hook($this->plugin->main_file, array(__CLASS__, 'register_uninstall_hook'));
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

        public function sanitize_plugin_slug($slug)
        {
            return str_replace("-", "_", trim($slug));
        }
    }

}
