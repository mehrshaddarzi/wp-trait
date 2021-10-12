<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\Response')) {

    class Response
    {
        public function json($data = [], $status_code = 200, $headers = [])
        {
            if (defined('REST_REQUEST') && REST_REQUEST) {
                return new \WP_HTTP_Response($data, $status_code, $headers);
            }

            wp_send_json($data, $status_code);
        }
    }
}