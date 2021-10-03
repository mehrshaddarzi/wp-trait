<?php

namespace WPTrait\Collection;

use WPTrait\Utils\Arr;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\RestAPI')) {

    class RestAPI
    {

        public function add_route($namespace, $route, $args = [], $override = false)
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
            $args['methods'] = strtoupper($args['methods']);

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

        public function request($type = 'GET', $route = '', $params = [])
        {
            $request = new \WP_REST_Request($type, '/' . $route);
            $request->set_query_params($params);
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

    }

}
