<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Request')) {

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

        public function input($name)
        {
            $inputs = $this->all();
            return (isset($inputs->{$name}) ? $inputs->{$name} : null);
        }

        public function query($name)
        {
            $inputs = $this->all('GET');
            return (isset($inputs->{$name}) ? $inputs->{$name} : null);
        }

        public function only($array = array())
        {
            $inputs = $this->all();
            foreach ($array as $name) {
                $_array[$name] = null;
            }
            foreach ($inputs as $name => $value) {
                if (in_array($name, (array)$array)) {
                    $_array[$name] = $value;
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