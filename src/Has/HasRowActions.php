<?php

namespace WPTrait\Has;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('HasRowActions')) {

    trait HasRowActions
    {

        public function register_row_actions($row_action)
        {
            /**
             * post: post_row_actions
             * taxonomy: {$taxonomy}_row_actions
             */
            $this->add_filter($row_action . '_row_actions', 'row_actions', 10, 2);
        }

        public function row_actions($actions, $object)
        {
            return $actions;
        }

    }

}