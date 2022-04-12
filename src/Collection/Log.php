<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\Log')) {

    class Log
    {
        public function add($log = '', $type = 'debug', $condition = null)
        {
            if ($this->condition($condition)) {
                $type = $this->location($type);
                $text = $this->sanitize($log, $type);
                return error_log($text, 3, $type);
            }

            return false;
        }

        private function condition($condition = null)
        {
            if (is_null($condition)) {
                return (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG);
            }

            return $condition;
        }

        private function location($type = 'debug')
        {
            $type = str_ireplace('.log', '', $type);

            if (defined('WP_DEBUG_LOG') and is_string(WP_DEBUG_LOG)) {
                return str_replace(basename(WP_DEBUG_LOG), $type . '.log', WP_DEBUG_LOG);
            }

            return WP_CONTENT_DIR . '/' . $type . '.log';
        }

        private function sanitize($log = '', $type = 'debug')
        {
            if (is_array($log) || is_object($log)) {
                $log = json_encode($log, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            $date = apply_filters('wp_trait_log_date', date("Y-m-d H:i:s") . " UTC", $type);
            return "[" . $date . "] $log\n";
        }
    }
}
