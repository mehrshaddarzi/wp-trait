<?php

namespace WPTrait\Http;

use WPTrait\Abstracts\Result;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\HTTP\Download')) {

    class Download extends Result
    {
        /**
         * File Url
         *
         * @var string
         */
        protected string $url = '';

        /**
         * Request TimeOut
         *
         * @var float
         */
        protected float $timeout = 300;

        /**
         * Whether to perform Signature Verification.
         *
         * @var bool
         */
        protected bool $signature_verification = false;

        /**
         * Reject unsafe urls
         *
         * @var bool
         */
        protected bool $reject_unsafe_urls = true;

        public function __construct($url, $timeout = 300, $signature_verification = false, $reject_unsafe_urls = true)
        {
            $this->url = $url;
            $this->timeout = $timeout;
            $this->signature_verification = $signature_verification;
            $this->reject_unsafe_urls = $reject_unsafe_urls;
        }

        public function timeout($timeout): static
        {
            $this->timeout = $timeout;
            return $this;
        }

        public function verification($bool): static
        {
            $this->signature_verification = (bool)$bool;
            return $this;
        }

        public function unsafe($unsafe = true): static
        {
            $this->reject_unsafe_urls = !($unsafe === true);
            return $this;
        }

        public function execute(): bool|static
        {
            // Check Url
            if (empty($this->url)) {
                return false;
            }

            // Check Function Exists
            if (!function_exists('download_url')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }

            // Setup allow unsafe url filter
            $filter_unsafe_url = function ($parsed_args, $url) {
                $parsed_args['reject_unsafe_urls'] = false;
                return $parsed_args;
            };

            // Add http_request_args filter
            if (!$this->reject_unsafe_urls) {
                add_filter('http_request_args', $filter_unsafe_url, 10, 2);
            }

            // Send request
            $this->response = download_url($this->url, $this->timeout, $this->signature_verification);

            // Remove http_request_args filter
            if (!$this->reject_unsafe_urls) {
                remove_filter('http_request_args', $filter_unsafe_url);
            }

            // Return
            return $this;
        }

        public function tmp(): string
        {
            if (!$this->hasError()) {
                return $this->response;
            }

            return '';
        }

        public function copy($path): bool
        {
            if ($this->hasError()) {
                return false;
            }

            $copy = copy($this->response, $path);
            @unlink($this->response);
            return $copy;
        }
    }
}
