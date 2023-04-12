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

        public function __construct($url, $timeout = 300, $signature_verification = false)
        {
            $this->url = $url;
            $this->timeout = $timeout;
            $this->signature_verification = $signature_verification;
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

            // Send request
            $this->response = download_url($this->url, $this->timeout, $this->signature_verification);

            // Return
            return $this;
        }

        public function getFilename(): \WP_Error|string
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
