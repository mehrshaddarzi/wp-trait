<?php

namespace WPTrait\Http;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\HTTP\Request')) {

    class Request
    {
        public function all($var = 'REQUEST')
        {
            $_array = [];
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

        public function only($array = [], $filter = null)
        {
            $_array = [];
            $inputs = $this->all();
            foreach ($inputs as $name => $value) {
                $_array[$name] = (in_array($name, (array)$array) ? $this->filter($value, $filter) : null);
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

        public function numeric($name, $positive = null)
        {
            $input = $this->input($name, ['trim']);
            $numeric = ($this->filled($name) and is_numeric($input));
            if ($positive === true) {
                return ($numeric and $input > 0);
            } elseif ($positive === false) {
                return ($numeric and $input < 0);
            }

            return $numeric;
        }

        public function equal($name, $value)
        {
            return ($this->input($name) == $value);
        }

        public function whenFilled($name, $filter = null, $default = false)
        {
            return $this->filled($name) ? $this->input($name, $filter) : $default;
        }

        public function anyFilled($names)
        {
            $names = is_array($names) ? $names : func_get_args();

            foreach ($names as $name) {
                if ($this->filled($name)) {
                    return true;
                }
            }

            return false;
        }

        public function enum($name, $array)
        {
            return (in_array($this->input($name), $array));
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

        public function is_rest()
        {
            #see https://developer.wordpress.org/reference/functions/rest_api_loaded/
            return (defined('REST_REQUEST') && REST_REQUEST);
        }

        public function is_ajax()
        {
            return wp_doing_ajax();
        }

        public function is_cron()
        {
            return wp_doing_cron();
        }

        public function is_xmlrpc()
        {
            return (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST);
        }

        public function is_cli()
        {
            return (defined('WP_CLI') && WP_CLI);
        }

        public function get_method()
        {
            $method = strtoupper($this->server('REQUEST_METHOD'));
            if (in_array($method, ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'PATCH', 'PURGE', 'TRACE'], true)) {
                return $method;
            }

            return 'GET';
        }

        public function is_method($name)
        {
            return ($this->get_method() == strtoupper($name));
        }

        private function getGlobalVariable($name = 'REQUEST')
        {
            switch (strtolower($name)) {
                case "get":
                    $return = ($_GET ?? []);
                    break;
                case "post":
                    $return = ($_POST ?? []);
                    break;
                case "file":
                case "files":
                    $return = ($_FILES ?? []);
                    break;
                case "cookie":
                    $return = ($_COOKIE ?? []);
                    break;
                case "server":
                    $return = ($_SERVER ?? []);
                    break;
                case "session":
                    $return = ($_SESSION ?? []);
                    break;
                default:
                    $return = ($_REQUEST ?? []);
            }

            return $return;
        }
    }
}
