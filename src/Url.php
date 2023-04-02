<?php

namespace WPTrait;

if (!class_exists('WPTrait\Url')) {

    class Url
    {
        /**
         * Get WordPress Site Url
         *
         * @var string
         */
        public string $site;

        /**
         * Get WordPress Home Url
         *
         * @var string
         */
        public string $home;

        /**
         * Get WordPress Globals Variables
         *
         * @var Globals
         */
        private Globals $global;

        public function __construct()
        {
            $this->home = $this->home();
            $this->site = $this->get();
            $this->global = new Globals();
        }

        /**
         * Get the current URL without the query string
         *
         * @return string
         */
        public function current(): string
        {
            return $this->get($this->global->wp->request);
        }

        /**
         * Get the current URL including the query string
         *
         * @return string
         */
        public function full(): string
        {
            return $this->generate($this->current(), $this->global->wp->query_vars);
        }

        /**
         * Get WordPress Site Url
         *
         * @param string $path
         * @param array $query
         * @param null $blog_id
         * @param null $scheme
         * @return string
         */
        public function get(string $path = '', array $query = [], $blog_id = null, $scheme = null): string
        {
            return $this->generate(get_site_url($blog_id, $path, $scheme), $query);
        }

        /**
         * Get WordPress Home Url
         *
         * @param string $path
         * @param array $query
         * @param null $blog_id
         * @param null $scheme
         * @return string
         */
        public function home(string $path = '', array $query = [], $blog_id = null, $scheme = null): string
        {
            return $this->generate(get_home_url($blog_id, $path, $scheme), $query);
        }

        /**
         * Get WordPress Admin Url
         *
         * @param string $path
         * @param array $query
         * @param $blog_id
         * @return string
         */
        public function admin(string $path = '', array $query = [], $blog_id = null): string
        {
            return $this->generate(get_admin_url($blog_id, $path), $query);
        }

        /**
         * Get WordPress Admin Ajax Url
         *
         * @param string $action
         * @param array $query
         * @return string
         */
        public function ajax(string $action = '', array $query = []): string
        {
            return $this->generate($this->admin('admin-ajax.php'), array_merge(['action' => $action], $query));
        }

        /**
         * Get WordPress REST URl
         *
         * @param string $path
         * @param array $query
         * @param $blog_id
         * @return string
         */
        public function rest(string $path = '/', array $query = [], $blog_id = null): string
        {
            return $this->generate(get_rest_url($blog_id, $path), $query);
        }

        /**
         * Get WordPress REST API Prefix
         *
         * @return string
         */
        public function restPrefix(): string
        {
            return rest_get_url_prefix();
        }

        /**
         * Get WordPress CronJob URL
         *
         * @return string
         */
        public function cron(): string
        {
            return $this->get('wp-cron.php?doing_wp_cron');
        }

        /**
         * Parse Url
         *
         * @param string $url
         * @return array|false|mixed|null
         * @see https://www.php.net/manual/en/function.parse-url.php
         */
        public function parse(string $url = ''): mixed
        {
            $url = (empty($url) ? $this->full() : $url);
            return wp_parse_url($url);
        }

        /**
         * Generate Url
         *
         * @param $url
         * @param array $query
         * @return string
         */
        public function generate($url, array $query = []): string
        {
            return add_query_arg($query, $url);
        }

        /**
         * Checks and cleans a URL
         *
         * @param $url
         * @param $protocols
         * @param string $context
         * @return string
         */
        public function esc($url, $protocols = null, string $context = 'display'): string
        {
            return esc_url($url, $protocols, $context);
        }

        /**
         * Sanitizes a URL for database or redirect usage
         *
         * @param $url
         * @param $protocols
         * @return string
         */
        public function sanitize($url, $protocols = null): string
        {
            return esc_url_raw($url, $protocols);
        }

        /**
         * validating a URL
         *
         * @param $url
         * @return bool
         */
        public function isValidate($url): bool
        {
            return $this->sanitize($url) === $url;
        }

    }

}
