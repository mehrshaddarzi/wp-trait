<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\RestAPI')) {

    class RestAPI
    {

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
