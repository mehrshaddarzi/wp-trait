<?php
/**
 * Plugin Name:       My Basics Plugin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            John Smith
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       plugin-slug
 * Domain Path:       /languages
 */

# Load Package
require_once dirname(__FILE__) . '/vendor/autoload.php';

# Use Trait
use PLUGIN_SLUG\Admin;
use PLUGIN_SLUG\Country;
use WPTrait\Plugin;

# Define Class
class PLUGIN_SLUG extends Plugin
{

    /**
     * Admin Area
     *
     * @var Admin
     */
    public $Admin;

    /**
     * Country Taxonomy
     *
     * @var Country
     */
    public $Country;

    public function __construct($slug, $args = [])
    {
        parent::__construct($slug, $args);
    }

    public function instantiate()
    {
        $this->Admin = new Admin($this->plugin);
        $this->Country = new Country($this->plugin, 'country', __('Country', $this->plugin->textDomain), ['post'], []);
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

new PLUGIN_SLUG('plugin-slug');