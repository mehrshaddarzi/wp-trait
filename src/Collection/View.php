<?php

namespace WPTrait\Collection;

use WPTrait\Plugin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\View')) {
    class View
    {
        /**
         * Attributes
         * 
         * @var array
         */
        public array $attributes;

        /**
         * View Path
         * 
         * @var string
         */
        public string $path;

        /**
         * @param string $view
         * @param string $path
         * @param object|Plugin $plugin
         */
        public function __construct($path = '', $plugin = null)
        {
            $this->attributes = [];
            $this->set_path($path, $plugin);
        }

        /**
         * @param string $path
         * @param object|Plugin $plugin
         * 
         * @return void
         */
        protected function set_path($path = '', $plugin = null)
        {
            if (!$path && $plugin) {
                $path = $plugin->path . 'templates';
            }

            $this->path = $path;
        }

        /**
         * @param string|array $key_or_array
         * @param mixed $value
         * 
         * @return self
         */
        public function attribute($key_or_array, $value = null)
        {
            if (is_array($key_or_array)) {
                $this->attributes = array_merge($this->attributes, $key_or_array);
            } else {
                $this->attributes[$key_or_array] = $value;
            }

            return $this;
        }

        /**
         * @param string $view
         * @param array $data
         * @param array $merge_data
         * 
         * @return string
         */
        public function render($view = null, $data = [], $merge_data = [])
        {
            $view = $this->resolvePath($view);
            $output = '';

            if (!is_file($view) && !is_readable($view)) {
                throw new \Exception('Invalid view file: ' . $view);
            }

            $data = array_merge($data, $merge_data);
            $data = array_merge($this->attributes, $data);

            try {
                ob_start();

                if ($data) {
                    extract($data);
                }

                include $view;

                $output = ob_get_clean();
            } catch (\Exception $e) {
                ob_end_clean();
                throw $e;
            }

            return $output;
        }

        /**
         * @param string $path
         * 
         * @return string
         */
        protected function resolvePath($path)
        {
            $view_path = '';

            foreach (explode('.', $path) as $path) {
                $view_path .= '/' . $path;
            }

            return $this->path . $view_path . '.php';
        }

        public function __set($name, $value)
        {
            $this->attributes[$name] = $value;
        }

        public function __invoke($view = null)
        {
            return $this->render($view, $this->attributes);
        }
    }
}