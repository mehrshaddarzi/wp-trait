<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\RestAPI')) {

    class RestAPI
    {

        public function internal($type = 'GET', $route = '', $arg = [])
        {
            $defaults = [
                'body' => [],
                'query' => [],
                'headers' => [],
                'file' => []
            ];
            $args = wp_parse_args($arg, $defaults);

            $request = new \WP_REST_Request($type, '/' . ltrim($route, "/"));

            # Header
            if (!empty($args['headers'])) {
                $request->set_headers($args['headers'], $override = true);
            }

            # query [GET]
            if (!empty($args['query'])) {
                $request->get_query_params($args['query']);
            }

            # body [POST]
            if (!empty($args['body'])) {
                $request->set_body_params($args['body']);
            }

            # file [FILE]
            if (!empty($args['file'])) {
                $request->set_file_params($args['file']);
            }

            $do = rest_do_request($request);
            if ($do->is_error()) {
                $error_data = $do->get_error_data();
                $status = isset($error_data['status']) ? $error_data['status'] : 500;
                return [
                    'error' => true,
                    'message' => $do->get_error_message(),
                    'response' => array(
                        'code' => $status,
                        'message' => get_status_header_desc($status),
                    )
                ];
            }

            # wp-includes/class-wp-http-requests-response.php#L185
            return $do->to_array();
        }

    }

}
