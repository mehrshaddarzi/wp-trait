<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\dbDelta')) {

    trait dbDelta
    {
        public function dbDelta($arg = null)
        {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            if (is_null($arg)) {
                return false;
            }

            if (is_string($arg)) {
                $sql = $arg;
            } else {

                $defaults = [
                    'table' => '',
                    'columns' => [],
                    'option' => []
                ];
                $args = wp_parse_args($arg, $defaults);

                $sql = "CREATE TABLE {$args['table']} (\n";
                foreach ($args['columns'] as $key => $val) {
                    $sql .= "{$key} {$val},\n";
                }
                foreach ($args['option'] as $option) {
                    $sql .= "{$option} \n";
                }
                $sql .= ") $charset_collate;";
            }

            # Run dbDelta [https://codex.wordpress.org/Creating_Tables_with_Plugins#Creating_or_Updating_the_Table]
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            return dbDelta($sql);
        }
    }

}