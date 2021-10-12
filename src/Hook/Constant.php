<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\Constant')) {

    trait Constant
    {
        public function constant($name)
        {
            global $wp_version;

            switch (strtolower($name)) {
                case "version":
                case "wp_version":
                    return $wp_version;
                case "root":
                case "abspath":
                    return ABSPATH;
                case "home":
                    return get_home_url();
                case "debug":
                case "wp_debug":
                    return WP_DEBUG;
                case "script":
                case "script_debug":
                    return SCRIPT_DEBUG;
                case "cache":
                case "wp_cache":
                    return WP_CACHE;
                case "minute":
                    return MINUTE_IN_SECONDS;
                case "hour":
                    return HOUR_IN_SECONDS;
                case "day":
                    return DAY_IN_SECONDS;
                case "week":
                    return WEEK_IN_SECONDS;
                case "month":
                    return MONTH_IN_SECONDS;
                case "year":
                    return YEAR_IN_SECONDS;
                case "content_url":
                    return WP_CONTENT_URL;
                case "content_dir":
                    return WP_CONTENT_DIR;
                case "plugin_dir":
                    return WP_PLUGIN_DIR;
                case "plugin_url":
                    return WP_PLUGIN_URL;
                case "mu_plugin_dir":
                    return WPMU_PLUGIN_DIR;
                case "mu_plugin_url":
                    return WPMU_PLUGIN_URL;
                case "template_path":
                    return TEMPLATEPATH;
                case "theme_root":
                    return get_theme_root();
                default:
                    return $name;
            }
        }
    }

}