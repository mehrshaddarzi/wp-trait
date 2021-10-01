<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\RESTAPI')) {

    class RESTAPI
    {

        public function add_route($namespace, $route, $args = array(), $override = false)
        {
            # alias
            $args = $this->convertAliasArg($args, [
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
            $args['methods'] = strtoupper($args['methods']);

            # Register route
            register_rest_route($namespace, $route, $args, $override);
        }

        public static function request($args = array())
        {
            $defaults = array(
                'type' => 'GET',
                'namespace' => '',
                'route' => '',
                'params' => array()
            );
            $args = wp_parse_args($args, $defaults);

            # Send Request
            $request = new \WP_REST_Request($args['type'], '/' . ltrim($args['namespace'], "/") . '/' . $args['route']);
            $request->set_query_params($args['params']);
            $response = rest_do_request($request);
            $server = rest_get_server();
            return $server->response_to_data($response, false);
        }

        public function url($path = '/', $blog_id = null, $scheme = 'rest')
        {
            return get_rest_url($blog_id, $path, $scheme);
        }

        public function prefix()
        {
            return rest_get_url_prefix();
        }

        private function convertAliasArg($array = array(), $alias = array())
        {
            $_array = array();
            foreach ($array as $key => $value) {
                $_array[(isset($alias[$key]) ? $alias[$key] : $key)] = $value;
            }

            return $_array;
        }
    }

}
