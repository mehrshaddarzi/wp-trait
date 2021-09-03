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

        public $slug;
        public $main_file;
        public $url;
        public $path;
        public $version;
        public $text_domain;
        public $plugin_data = array();

        public function __construct($slug, $main_file = __FILE__)
        {
            // Set Plugin Slug
            $this->slug = $slug;
            $this->main_file = $main_file;

            // Define Variable
            $this->define_constants();

            // PHP Notice Version
            if (isset($this->plugin_data['RequiresPHP']) and !empty($this->plugin_data['RequiresPHP'])) {
                if (version_compare(PHP_VERSION, $this->plugin_data['RequiresPHP'], '<=')) {
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

            // Plugin Loaded Action
            do_action($this->slug . '_loaded');
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

            $this->url = plugins_url('', $this->main_file);
            $this->path = plugin_dir_path($this->main_file);
            $this->plugin_data = get_plugin_data($this->main_file);
            $this->version = $this->plugin_data['Version'];
        }

        protected function includes()
        {
        }

        protected function init_hooks()
        {
            // Load Text Domain
            if (isset($this->plugin_data['TextDomain']) and !empty($this->plugin_data['TextDomain'])) {
                load_plugin_textdomain($this->plugin_data['TextDomain'], false, wp_normalize_path($this->path . '/languages'));
            }

            // register_activation_hook
            if (method_exists($this, 'register_activation_hook')) {
                register_activation_hook($this->main_file, array($this, 'register_activation_hook'));
            }

            // register_deactivation_hook
            if (method_exists($this, 'register_deactivation_hook')) {
                register_deactivation_hook($this->main_file, array($this, 'register_deactivation_hook'));
            }

            // register_uninstall_hook
            if (method_exists($this, 'register_uninstall_hook')) {
                register_uninstall_hook($this->main_file, array($this, 'register_uninstall_hook'));
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
            $error = __('Your installed PHP Version is: ', $this->text_domain) . PHP_VERSION . '. ';
            $error .= __('The <strong>' . $this->plugin_data['Title'] . '</strong> plugin requires PHP version <strong>', $this->text_domain) . $this->plugin_data['RequiresPHP'] . __('</strong> or greater.', $this->text_domain);
            $this->add_alert($error, 'error', true, true);
        }
    }

}