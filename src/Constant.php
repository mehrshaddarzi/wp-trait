<?php

namespace WPTrait;

if (!class_exists('WPTrait\Constant')) {

    class Constant
    {

        /**
         * ABSPATH
         *
         * @var string
         */
        public $root;

        /**
         * WP_DEBUG
         *
         * @var bool
         */
        public $debug;

        /**
         * WordPress environment type
         *
         * @var string
         */
        public $environment;

        /**
         * SCRIPT_DEBUG
         *
         * @var bool
         */
        public $script_debug;

        /**
         * WP_CACHE
         *
         * @var bool
         */
        public $cache;

        /**
         * MINUTE_IN_SECONDS
         *
         * @var int
         */
        public $minute;

        /**
         * HOUR_IN_SECONDS
         *
         * @var int
         */
        public $hour;

        /**
         * DAY_IN_SECONDS
         *
         * @var int
         */
        public $day;

        /**
         * WEEK_IN_SECONDS
         *
         * @var int
         */
        public $week;

        /**
         * MONTH_IN_SECONDS
         *
         * @var int
         */
        public $month;

        /**
         * YEAR_IN_SECONDS
         *
         * @var int
         */
        public $year;

        /**
         * Retrieves the URL to the content directory
         *
         * @var string
         */
        public $content_url;

        /**
         * WP_CONTENT_DIR
         *
         * @var string
         */
        public $content_dir;

        /**
         * Get Plugins dir directory
         *
         * @var string
         */
        public $plugin_dir;

        /**
         * Get Plugins dir url
         *
         * @var string
         */
        public $plugin_url;

        /**
         * Get WordPress Uploads Directory
         *
         * @var \WP_Uploads_Dir_meta
         */
        public $uploads;

        /**
         * MU-plugins directory path
         *
         * @var string
         */
        public $mu_plugin_dir;

        /**
         * MU-plugins directory url
         *
         * @var string
         */
        public $mu_plugin_url;

        /**
         * Template directory path for the active theme
         *
         * @var string
         */
        public $template_path;

        /**
         * Template directory url for the active theme
         *
         * @var string
         */
        public $template_url;

        /**
         * Themes directory path
         *
         * @var string
         */
        public $theme_root;

        /**
         * Themes directory url
         *
         * @var string
         */
        public $theme_root_url;

        public function __construct()
        {
            foreach (array_keys(get_object_vars($this)) as $property) {
                $this->{$property} = $this->{'get_' . $property}();
            }
        }

        private function get_root(): string
        {
            return ABSPATH;
        }

        private function get_debug(): bool
        {
            if (defined('WP_DEBUG') and is_bool(WP_DEBUG)) {
                return WP_DEBUG;
            }

            return false;
        }

        private function get_environment(): string
        {
            return wp_get_environment_type();
        }

        private function get_script_debug(): bool
        {
            return SCRIPT_DEBUG;
        }

        private function get_cache(): bool
        {
            return WP_CACHE;
        }

        private function get_minute(): int
        {
            return MINUTE_IN_SECONDS;
        }

        private function get_hour(): int
        {
            return HOUR_IN_SECONDS;
        }

        private function get_day(): int
        {
            return DAY_IN_SECONDS;
        }

        private function get_week(): int
        {
            return WEEK_IN_SECONDS;
        }

        private function get_month(): int
        {
            return MONTH_IN_SECONDS;
        }

        private function get_year(): int
        {
            return YEAR_IN_SECONDS;
        }

        private function get_content_url(): string
        {
            return WP_CONTENT_URL;
        }

        private function get_content_dir(): string
        {
            return WP_CONTENT_DIR;
        }

        private function get_plugin_dir(): string
        {
            return WP_PLUGIN_DIR;
        }

        private function get_plugin_url(): string
        {
            return WP_PLUGIN_URL;
        }

        private function get_uploads()
        {
            return (object)wp_get_upload_dir();
        }

        private function get_mu_plugin_dir(): string
        {
            return WPMU_PLUGIN_DIR;
        }

        private function get_mu_plugin_url(): string
        {
            return WPMU_PLUGIN_URL;
        }

        private function get_template_path(): string
        {
            return get_template_directory();
        }

        private function get_template_url(): string
        {
            return get_template_directory_uri();
        }

        private function get_theme_root(): string
        {
            return get_theme_root();
        }

        private function get_theme_root_url(): string
        {
            return get_theme_root_uri();
        }

    }

}
