<?php

namespace WPTrait\Collection;

use WPTrait\Utils\Arr;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\Meta')) {
    class Meta
    {

        /**
         * Type Of Meta Data:
         * post
         * attachment
         * user
         * term
         * comment
         *
         * @var string
         */
        public $type;

        /**
         * Object id
         * @var int
         */
        public $object_id;

        public function __construct($type = 'post', $object_id = null)
        {
            $this->type = $this->setTypeMetaData($type);
            $this->object_id = $object_id;
        }

        public function get($meta, $object_id = null, $type = null)
        {
            $arg = $this->sanitizeArg($object_id, $type);
            $func = 'get_' . $arg->type . '_meta';
            return $func($arg->object_id, $meta, true);
        }

        public function all($object_id = null, $type = null)
        {
            $arg = $this->sanitizeArg($object_id, $type);
            $func = 'get_' . $arg->type . '_meta';
            return array_map(function ($a) {
                return $a[0];
            }, $func($arg->object_id));
        }

        public function only($meta_keys = [], $object_id = null, $type = null)
        {
            return Arr::only($this->all($object_id, $type), $meta_keys);
        }

        public function except($meta_keys = [], $object_id = null, $type = null)
        {
            return Arr::except($this->all($object_id, $type), $meta_keys);
        }

        public function save($meta, $value, $object_id = null, $type = null)
        {
            $arg = $this->sanitizeArg($object_id, $type);
            $func = 'update_' . $arg->type . '_meta';
            return $func($arg->object_id, $meta, $value); #return meta id
        }

        public function update(...$args)
        {
            return $this->save(...$args);
        }

        public function add($meta, $value, $unique = false, $object_id = null, $type = null)
        {
            $arg = $this->sanitizeArg($object_id, $type);
            $func = 'add_' . $arg->type . '_meta';
            return $func($arg->object_id, $meta, $value, $unique); #return meta id
        }

        public function delete($meta, $object_id = null, $type = null)
        {
            $arg = $this->sanitizeArg($object_id, $type);
            $func = 'delete_' . $arg->type . '_meta';
            return $func($arg->object_id, $meta);
        }

        public function clean($object_id = null, $type = null)
        {
            $all = $this->all($object_id, $type);
            foreach ($all as $name => $value) {
                $this->delete($name, $object_id, $type);
            }
        }

        private function setTypeMetaData($type)
        {
            if (in_array($type, ['post', 'attachment'])) {
                return 'post';
            }

            return $type;
        }

        private function sanitizeArg($object_id, $type)
        {
            $return = new \stdClass();
            $return->object_id = (is_null($object_id) ? $this->object_id : $object_id);
            $return->type = (is_null($type) ? $this->type : $type);
            return $return;
        }
    }
}
