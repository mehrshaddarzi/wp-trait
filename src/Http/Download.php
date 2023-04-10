<?php

namespace WPTrait\Http;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\HTTP\Download')) {

    class Download
    {
        /**
         * File Url
         *
         * @var string
         */
        public string $url = '';

        /**
         * Request TimeOut
         *
         * @var float
         */
        public float $timeout = 300;

        /**
         * Whether to perform Signature Verification.
         *
         * @var bool
         */
        protected bool $signature_verification = false;

        /**
         * HTTP Response
         */
        public string|\WP_Error $response;

        public function __construct($url = '')
        {
            $this->url = $url;
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

        public function send()
        {
            // Check Url
            if (empty($this->url)) {
                return false;
            }

            // Check Function Exists
            if (!function_exists('download_url')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }

            // Send request
            $this->response = download_url($this->url, $this->timeout, $this->signature_verification);

            // Return
            return $this;
        }

        public function hasError(): bool
        {
            return is_wp_error($this->response);
        }

        public function getErrorMessage(): bool
        {
            if ($this->hasError()) {
                return $this->response->get_error_message();
            }

            return '';
        }

        public function getFilename(): \WP_Error|string
        {
            if (!$this->hasError()) {
                return $this->response;
            }

            return '';
        }

        public function copyTo($path): bool
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
