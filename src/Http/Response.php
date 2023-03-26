<?php

namespace WPTrait\Http;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\HTTP\Response')) {

    class Response
    {
        public function json($data = [], $status_code = 200, $headers = [])
        {
            if (defined('REST_REQUEST') && REST_REQUEST) {
                return new \WP_HTTP_Response($data, $status_code, $headers);
            }

            wp_send_json($data, $status_code);
        }

        public function success($data = [], $status_code = 200, $headers = [])
        {
            return $this->json(['success' => true, 'data' => $data], $status_code, $headers);
        }

        public function error($data = [], $status_code = 400, $headers = [])
        {
            return $this->json(['success' => false, 'data' => $data], $status_code, $headers);
        }
    }
}