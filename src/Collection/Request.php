<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\Request')) {

    class Request
    {
        public function all($var = 'REQUEST')
        {
            $_array = array();
            foreach ((array)$var as $key) {
                $_array = array_merge($_array, $this->getGlobalVariable($key));
            }
            return (object)$_array;
        }

        public function input($name, $filter = null)
        {
            $inputs = $this->all();
            return (isset($inputs->{$name}) ? $this->filter($inputs->{$name}, $filter) : null);
        }

        private function filter($value, $filter = [])
        {
            foreach ((array)$filter as $func) {
                $value = $func($value);
            }

            return $value;
        }

        public function query($name, $filter = null)
        {
            $inputs = $this->all('GET');
            return (isset($inputs->{$name}) ? $this->filter($inputs->{$name}, $filter) : null);
        }

        public function only($array = array(), $filter = null)
        {
            $inputs = $this->all();
            foreach ($array as $name) {
                $_array[$name] = null;
            }
            foreach ($inputs as $name => $value) {
                if (in_array($name, (array)$array)) {
                    $_array[$name] = $this->filter($value, $filter);
                }
            }

            return (object)$_array;
        }

        public function has($name)
        {
            $inputs = $this->all();
            return isset($inputs->{$name});
        }

        public function filled($name)
        {
            $inputs = $this->all();
            return (isset($inputs->{$name}) and !empty(trim($inputs->{$name})));
        }

        public function numeric($name)
        {
            $inputs = $this->all();
            return ($this->filled($name) and is_numeric(trim($inputs->{$name})));
        }

        public function equal($name, $value)
        {
            return ($this->input($name) == $value);
        }

        public function redirect($location, $status = 302)
        {
            if (function_exists('wp_redirect')) {
                wp_redirect($location, $status);
            }
        }

        public function cookie($name)
        {
            $inputs = $this->all('COOKIE');
            return (isset($inputs->{$name}) ? $inputs->{$name} : null);
        }

        public function server($name)
        {
            $inputs = $this->all('SERVER');
            return (isset($inputs->{$name}) ? $inputs->{$name} : null);
        }

        public function file($name)
        {
            $files = $this->all('files');
            /**
             * ['name' => '', 'type' => 'image/png', 'tmp_name' => '', 'error' => 0, 'size' => '']
             */
            return (isset($files->{$name}) ? $files->{$name} : null);
        }

        public function hasFile($name)
        {
            $files = $this->all('files');
            return (isset($files->{$name}) and !empty($files->{$name}["name"]));
        }

        public function json($data = [], $status_code = 200, $headers = [])
        {
            if ($this->is_rest()) {
                return new \WP_HTTP_Response($data, $status_code, $headers);
            }

            wp_send_json($data, $status_code);
        }

        public function is_rest()
        {
            #see https://developer.wordpress.org/reference/functions/rest_api_loaded/
            return (defined('REST_REQUEST') && REST_REQUEST);
        }

        public function is_ajax()
        {
            return wp_doing_ajax();
        }

        public function new($url, $method = 'GET', $args = array())
        {
            # alias
            if (isset($args['ssl'])) {
                $args['sslverify'] = $args['ssl'];
                unset($args['sslverify']);
            }

            # https://developer.wordpress.org/reference/classes/WP_Http/request/
            return wp_remote_request($url, array_merge(array('method' => strtoupper($method)), $args));
        }

        private function getGlobalVariable($name = 'REQUEST')
        {
            switch (strtolower($name)) {
                case "get":
                    return (isset($_GET) ? $_GET : array());
                    break;
                case "post":
                    return (isset($_POST) ? $_POST : array());
                    break;
                case "file":
                case "files":
                    return (isset($_FILES) ? $_FILES : array());
                    break;
                case "cookie":
                    return (isset($_COOKIE) ? $_COOKIE : array());
                    break;
                case "server":
                    return (isset($_SERVER) ? $_SERVER : array());
                    break;
                default:
                    return (isset($_REQUEST) ? $_REQUEST : array());
            }
        }
    }
}