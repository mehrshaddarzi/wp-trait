<?php

namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\RowActions')) {

    trait RowActions
    {

        public function bootRowActions($arg = [])
        {
            $defaults = [
                'method' => 'row_actions',
                'type' => 'post',
                'priority' => 10,
            ];
            $args = wp_parse_args($arg, $defaults);

            /**
             * post: post_row_actions
             * taxonomy: {$taxonomy}_row_actions
             * user: user_row_actions
             */
            $this->add_filter($args['type'] . '_row_actions', $args['method'], $args['priority'], 2);
        }

        public function row_actions($actions, $object)
        {
            return $actions;
        }

    }

}