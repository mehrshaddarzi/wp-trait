<?php

namespace WPTrait\Collection;

use WPTrait\Utils\Arr;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\Route')) {

    class Route
    {

        public function add($namespace, $route, $args = [], $override = false)
        {
            # alias
            $args = Arr::alias($args, [
                'method' => 'methods',
                'function' => 'callback',
                'arg' => 'args',
                'permission' => 'permission_callback',
            ]);

            # Check not exist permission
            if (!isset($args['permission_callback'])) {
                $args['permission_callback'] = function (\WP_REST_Request $request) {
                    return true;
                };
            }

            # Sanitize method name
            $args['methods'] = array_map('strtoupper', (array) $args['methods']);

            # Sanitize Callback Method
            if (is_string($args['callback'])) {
                $args['callback'] = [$this, $args['callback']];
            }

            # alias args
            if (isset($args['args']) and !empty($args['args'])) {
                foreach ($args['args'] as $key => $params) {
                    $args['args'][$key] = Arr::alias($args['args'][$key], [
                        'require' => 'required',
                        'validate' => 'validate_callback',
                        'sanitize' => 'sanitize_callback'
                    ]);
                }
            }

            # Register route
            register_rest_route($namespace, $route, $args, $override);
        }

        public function remove($endpoint, $priority = 10)
        {
            add_filter('rest_endpoints', function ($endpoints) use ($endpoint, $priority) {
                $_sanitize = "/" . ltrim(str_replace(array("\"", "'"), "", $endpoint), "/");
                if (isset($endpoints[$_sanitize])) {
                    unset($endpoints[$_sanitize]);
                }
                return $endpoints;
            }, $priority);
        }

        public function all()
        {
            $rest = ['namespaces' => [], 'routes' => []];
            if (function_exists('rest_get_server')) {
                $wp_rest_server = rest_get_server();
                $rest['namespaces'] = $wp_rest_server->get_namespaces();
                $rest['routes'] = array_keys($wp_rest_server->get_routes());
            }

            return (object)$rest;
        }
    }
}
