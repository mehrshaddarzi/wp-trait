<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Collection\Hooks')) {

    trait Hooks
    {

        public function get_methods(): array
        {
            return get_class_methods($this);
        }

        public function search_methods($prefix): array
        {
            return array_filter($this->get_methods(), function ($method_name) use ($prefix) {
                return substr($method_name, 0, strlen($prefix)) == $prefix;
            });
        }

        public function add_filter($filter_name, $method = '', $priority = 10, $accepted_args = 1)
        {
            foreach ($this->search_methods($method) as $method) {
                add_filter($filter_name, [$this, $method], $priority, $accepted_args);
            }
        }

        public function add_action($filter_name, $method = '', $priority = 10, $accepted_args = 1)
        {
            foreach ($this->search_methods($method) as $method) {
                add_action($filter_name, [$this, $method], $priority, $accepted_args);
            }
        }
    }

}