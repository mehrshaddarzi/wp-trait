<?php

namespace WPTrait\Http;

use WPTrait\Abstracts\Result;
use WPTrait\Exceptions\Json\UnableDecodeJsonException;
use WPTrait\Utils\Json;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\HTTP\HTTP')) {

    class HTTP extends Result
    {
        /**
         * Request Url
         *
         * @var string
         */
        protected string $url = '';

        /**
         * Request Method
         *
         * @var string
         */
        protected string $method = 'GET';

        /**
         * Request TimeOut
         *
         * @var float
         */
        protected float $timeout = 5;

        /**
         * Number of allowed redirects
         *
         * @var int
         */
        protected int $redirection = 5;

        /**
         * Version of the HTTP protocol to use
         *
         * @var string
         */
        protected string $version = '1.0';

        /**
         * User Agent
         *
         * @var string
         */
        protected string $useragent = '';

        /**
         * Request Headers
         *
         * @var array
         */
        protected array $headers = [];

        /**
         * Request Cookies
         *
         * @var array
         */
        protected array $cookies = [];

        /**
         * Request Body
         *
         * @var string|array
         */
        protected string|array $body = '';

        /**
         * Check SSL Verify
         *
         * @var bool
         */
        protected bool $ssl = true;

        /**
         * Reject unsafe urls
         *
         * @var bool
         */
        protected bool $reject_unsafe_urls = true;

        /**
         * HTTP API Curl
         *
         * @var mixed
         */
        protected mixed $curl = null;

        public function __construct($url = '')
        {
            $this->url = $url;
        }

        public function timeout($timeout): static
        {
            $this->timeout = $timeout;
            return $this;
        }

        public function ssl($ssl): static
        {
            $this->ssl = (bool)$ssl;
            return $this;
        }

        public function redirection($number): static
        {
            $this->redirection = $number;
            return $this;
        }

        public function version($version): static
        {
            $this->version = $version;
            return $this;
        }

        public function unsafe($unsafe = true): static
        {
            $this->reject_unsafe_urls = !($unsafe === true);
            return $this;
        }

        public function useragent($agent): static
        {
            $this->useragent = $agent;
            return $this;
        }

        public function headers($array = []): static
        {
            $this->headers = $array;
            return $this;
        }

        public function cookies($array = []): static
        {
            $this->cookies = $array;
            return $this;
        }

        public function body($body): static
        {
            $this->body = $body;
            return $this;
        }

        public function json($array = [], $options = 0): static
        {
            if (is_array($array)) {
                $this->body = wp_json_encode($array, $options);
            }
            return $this;
        }

        public function curl($func): static
        {
            if (is_callable($func)) {
                $this->curl = $func;
            }

            return $this;
        }

        public function send(): bool|static
        {
            // Check Url
            if (empty($this->url)) {
                return false;
            }

            // Setup Params
            $this->setParams();

            // Setup http_api_curl action method
            $http_api_call = function ($handle) {
                call_user_func($this->curl, $handle);
            };

            // Setup allow unsafe url filter
            $filter_unsafe_url = function ($parsed_args, $url) {
                $parsed_args['reject_unsafe_urls'] = false;
                return $parsed_args;
            };

            // Add http_api_curl action
            if (is_callable($this->curl)) {
                add_action('http_api_curl', $http_api_call);
            }

            // Add http_request_args filter
            if (!$this->reject_unsafe_urls) {
                add_filter('http_request_args', $filter_unsafe_url, 10, 2);
            }

            // Send request
            $this->response = wp_remote_request($this->url, $this->params);

            // Remove http_api_curl action
            if (is_callable($this->curl)) {
                remove_action('http_api_curl', $http_api_call);
            }

            // Remove http_request_args filter
            if (!$this->reject_unsafe_urls) {
                remove_filter('http_request_args', $filter_unsafe_url);
            }

            // Return
            return $this;
        }

        public function setParams(): static
        {
            $this->params = [
                'method' => strtoupper($this->method),
                'timeout' => $this->timeout,
                'redirection' => $this->redirection,
                'httpversion' => $this->version,
                'headers' => $this->headers,
                'cookies' => $this->cookies,
                'sslverify' => $this->ssl
            ];

            if (!empty($this->useragent)) {
                $this->params['user-agent'] = $this->useragent;
            }

            if (!empty($this->body)) {
                $this->params['body'] = $this->body;
            }

            return $this;
        }

        public function getStatusCode()
        {
            if (isset($this->response['response']['code'])) {
                return $this->response['response']['code'];
            }

            return '';
        }

        public function getReasonPhrase()
        {
            if (isset($this->response['response']['message'])) {
                return $this->response['response']['message'];
            }

            return '';
        }

        public function getHeaders()
        {
            if (isset($this->response['headers'])) {
                return $this->response['headers'];
            }

            return [];
        }

        public function getHeader($name)
        {
            $headers = $this->getHeaders();
            if (isset($headers[$name])) {
                return $headers[$name];
            }

            return null;
        }

        public function hasHeader($name): bool
        {
            $headers = $this->getHeaders();
            return isset($headers[$name]);
        }

        public function getBody()
        {
            if (isset($this->response['body'])) {
                return $this->response['body'];
            }

            return '';
        }

        /**
         * Get Request Body as Array
         *
         * @param int $options
         * @return mixed|string
         */
        public function getJsonBody(int $options = 0): mixed
        {
            if (isset($this->response['body'])) {
                try {
                    return Json::decode($this->response['body'], true, 512, $options);
                } catch (UnableDecodeJsonException $e) {
                    return '';
                }
            }

            return '';
        }

        public function getCookies(): array
        {
            if (isset($this->response['cookies'])) {
                return $this->response['cookies'];
            }

            return [];
        }

        public function getCookie($name): null|\WP_Http_Cookie
        {
            $cookies = $this->getCookies();
            foreach ($cookies as $cookie) {
                if ($cookie->name === $name) {
                    /* @var \WP_Http_Cookie */
                    return $cookie;
                }
            }

            return null;
        }

        public function hasCookie($name): bool
        {
            return (!is_null($this->getCookie($name)));
        }

        private function request($url, $method): bool|static
        {
            $this->url = $url;
            $this->method = $method;
            return $this;
        }

        public function get($url): bool|static
        {
            return $this->request($url, 'get');
        }

        public function delete($url): bool|static
        {
            return $this->request($url, 'delete');
        }

        public function head($url): bool|static
        {
            return $this->request($url, 'head');
        }

        public function options($url): bool|static
        {
            return $this->request($url, 'options');
        }

        public function patch($url): bool|static
        {
            return $this->request($url, 'patch');
        }

        public function post($url): bool|static
        {
            return $this->request($url, 'post');
        }

        public function put($url): bool|static
        {
            return $this->request($url, 'put');
        }

        public function download($url, $timeout = 300, $signature_verification = false, $reject_unsafe_urls = true): Download
        {
            $download = new Download($url, $timeout, $signature_verification, $reject_unsafe_urls);
            return $download->execute();
        }
    }
}
