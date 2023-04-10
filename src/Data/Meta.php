<?php

namespace WPTrait\Data;

use WPTrait\Utils\Arr;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Data\Meta')) {

    class Meta
    {

        /**
         * Object id
         *
         * @var int
         */
        public int $id = 0;

        /**
         * Type of object metadata
         *
         * @var string
         */
        public string $type = 'post';

        public function __construct($id = 0, $type = '')
        {
            $this->id = $id;
            if (!empty($type)) {
                $this->type = $type;
            }
        }

        public function get($key)
        {
            return get_metadata($this->type, $this->id, $key, true);
        }

        public function all(): array
        {
            return array_map(function ($a) {
                return $a[0];
            }, get_metadata($this->type, $this->id, '', false));
        }

        public function only($keys = [])
        {
            return Arr::only($this->all(), $keys);
        }

        public function except($keys = [])
        {
            return Arr::except($this->all(), $keys);
        }

        public function save(mixed $key, mixed $value, mixed $prev_value = ''): mixed
        {
            $func = 'update_' . $this->type . '_meta';
            if (Arr::isAssoc($key)) {
                foreach ($key as $meta_key => $meta_value) {
                    $func($this->id, $meta_key, $meta_value);
                }
                return true;
            }

            return $func($this->id, $key, $value, $prev_value);
        }

        public function create(mixed $key, $value, $unique = false)
        {
            $func = 'add_' . $this->type . '_meta';
            if (Arr::isAssoc($key)) {
                foreach ($key as $meta_key => $meta_value) {
                    $func($this->id, $meta_key, $meta_value, $unique);
                }
                return true;
            }

            return $func($this->id, $key, $value, $unique);
        }

        public function delete(mixed $key, $meta_value = '')
        {
            $func = 'delete_' . $this->type . '_meta';
            if (is_array($key)) {
                foreach ($key as $meta_key) {
                    $func($this->id, $meta_key);
                }
                return true;
            }

            return $func($this->id, $key, $meta_value);
        }

        public function clean(): bool
        {
            $all = $this->all();
            foreach ($all as $key => $value) {
                $this->delete($key);
            }
            return true;
        }

    }
}
