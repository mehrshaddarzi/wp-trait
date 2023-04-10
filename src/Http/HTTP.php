<?php

namespace WPTrait\Http;

use WPTrait\Abstracts\Params;
use WPTrait\Exceptions\Json\UnableDecodeJsonException;
use WPTrait\Utils\Json;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\HTTP\HTTP')) {

    class HTTP extends Params
    {
        /**
         * Request Url
         *
         * @var string
         */
        public string $url = '';

        /**
         * Request Method
         *
         * @var string
         */
        public string $method = 'GET';

        /**
         * Request TimeOut
         *
         * @var float
         */
        public float $timeout = 5;

        /**
         * Number of allowed redirects
         *
         * @var int
         */
        public int $redirection = 5;

        /**
         * Version of the HTTP protocol to use
         *
         * @var string
         */
        public string $version = '1.0';

        /**
         * User Agent
         *
         * @var string
         */
        public string $useragent;

        /**
         * Request Headers
         *
         * @var array
         */
        public array $headers;

        /**
         * Request Cookies
         *
         * @var array
         */
        public array $cookies;

        /**
         * Request Body
         *
         * @var string|array
         */
        public string|array $body;

        /**
         * Check SSL Verify
         *
         * @var bool
         */
        public bool $ssl;

        /**
         * HTTP API Curl
         *
         * @var mixed
         */
        public mixed $curl = null;

        /**
         * HTTP Response
         *
         * @var array|\WP_Error
         */
        protected array|\WP_Error $response = [];

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

        public function useragent($agent): static
        {
            $this->version = $agent;
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

            // Add http_api_curl action
            if (is_callable($this->curl)) {
                add_action('http_api_curl', [$this, 'http_api_curl']);
            }

            // Send request
            $this->response = wp_remote_request($this->url, $this->params);

            // Remove http_api_curl action
            if (is_callable($this->curl)) {
                remove_action('http_api_curl', [$this, 'http_api_curl']);
            }

            // Return
            return $this;
        }

        public function http_api_curl($handle)
        {
            call_user_func_array($this->curl, $handle);
        }

        public function setParams(): static
        {
            $this->params = [
                'method' => strtoupper($this->method),
                'timeout' => $this->timeout,
                'redirection' => $this->redirection,
                'httpversion' => $this->version,
                'user-agent' => $this->useragent,
                'headers' => $this->headers,
                'cookies' => $this->cookies,
                'body' => $this->body,
                'sslverify' => $this->ssl
            ];

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

        public function getCookies()
        {
            if (isset($this->response['cookies'])) {
                return $this->response['cookies'];
            }

            return [];
        }

        public function getCookie($name)
        {
            $cookies = $this->getCookies();
            if (isset($cookies[$name])) {
                return $cookies[$name];
            }

            return null;
        }

        private function request($url, $method): bool|static
        {
            $this->url = $url;
            $this->method = $method;
            return $this->send();
        }

        public function get($url)
        {
            return $this->request($url, 'get');
        }

        public function delete($url)
        {
            return $this->request($url, 'delete');
        }

        public function head($url)
        {
            return $this->request($url, 'head');
        }

        public function options($url)
        {
            return $this->request($url, 'options');
        }

        public function patch($url)
        {
            return $this->request($url, 'patch');
        }

        public function post($url)
        {
            return $this->request($url, 'post');
        }

        public function put($url)
        {
            return $this->request($url, 'put');
        }

        public function download($url): Download
        {
            return new Download($url);
        }
    }
}
