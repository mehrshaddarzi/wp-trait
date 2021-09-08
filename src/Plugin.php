<?php

namespace WPTrait;

use WPTrait\Has\HasHooks;
use WPTrait\Has\HasNotice;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Plugin')) {

    abstract class Plugin
    {
        use HasHooks, HasNotice;

        public $plugin;

        public function __construct($slug, $args = array())
        {
            // Set Plugin Slug
            $this->plugin = new \stdClass();
            $this->plugin->slug = $slug;

            // Check Custom argument
            $default = array(
                'main_file' => __FILE__,
                'global' => $this->sanitize_plugin_slug($slug),
                'prefix' => $this->sanitize_plugin_slug($slug)
            );
            $arg = wp_parse_args($args, $default);

            // Set Main File
            $this->plugin->main_file = $arg['main_file'];

            // Set Prefix
            $this->plugin->prefix = $arg['prefix'];

            // Define Variable
            $this->define_constants();

            // PHP Notice Version
            if (isset($this->plugin->RequiresPHP) and !empty($this->plugin->RequiresPHP)) {
                if (version_compare(PHP_VERSION, $this->plugin->RequiresPHP, '<=')) {
                    $this->register_admin_notices();
                    return;
                }
            }

            // include PHP files
            if (method_exists($this, 'includes')) {
                $this->includes();
            }

            // init Wordpress hook
            $this->init_hooks();

            // Instantiate Object Class
            $this->instantiate();

            // Set Global Function
            if (!empty($arg['global']) and !is_null($arg['global'])) {
                $GLOBALS[$arg['global']] = $this;

                // Create global function for backwards compatibility.
                $function = 'function ' . $arg['global'] . '() { return $GLOBALS[\'' . $arg['global'] . '\']; }';
                eval($function);
            }

            // Plugin Loaded Action
            do_action($this->plugin->prefix . '_loaded');
        }

        public function __get($name)
        {
            return $this->$name;
        }

        protected function define_constants()
        {
            if (!function_exists('get_plugin_data')) {
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }

            $this->plugin = (object)array_merge((array)$this->plugin, (array)get_plugin_data($this->plugin->main_file));
            $this->plugin->url = plugins_url('', $this->plugin->main_file);
            $this->plugin->path = plugin_dir_path($this->plugin->main_file);
        }

        protected function includes()
        {
        }

        protected function instantiate()
        {
        }

        protected function init_hooks()
        {
            // Load Text Domain
            if (isset($this->plugin->TextDomain) and !empty($this->plugin->TextDomain)) {
                load_plugin_textdomain($this->plugin->TextDomain, false, wp_normalize_path($this->plugin->path . '/languages'));
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
                register_uninstall_hook($this->plugin->main_file, array($this, 'register_uninstall_hook'));
            }
        }

        protected function register_activation_hook()
        {
        }

        protected function register_deactivation_hook()
        {
        }

        protected function register_uninstall_hook()
        {
        }

        protected function admin_notices_php_version_notice()
        {
            if (!current_user_can('manage_options')) {
                return;
            }
            $error = __('Your installed PHP Version is: ', $this->plugin->TextDomain) . PHP_VERSION . '. ';
            $error .= __('The <strong>' . $$this->plugin->Title . '</strong> plugin requires PHP version <strong>', $this->plugin->TextDomain) . $this->plugin->RequiresPHP . __('</strong> or greater.', $this->plugin->TextDomain);
            $this->add_alert($error, 'error', true, true);
        }

        protected function sanitize_plugin_slug($slug)
        {
            return str_replace("-", "_", trim($slug));
        }
    }

}