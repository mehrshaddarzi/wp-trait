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
         * View Path
         * 
         * @var string
         */
        public $path;

        /** @var object|Plugin */
        private $plugin;
        
        /**
         * View Attributes
         * 
         * @var array
         */
        public $attributes = [];

        /**
         * @param string $view
         * @param string $path
         * @param object|Plugin $plugin
         */
        public function __construct($path = '', $plugin = null)
        {
            $this->setPath($path, $plugin);
            $this->plugin = $plugin;
        }

        /**
         * @param string $path
         * @param object|Plugin $plugin
         * 
         * @return void
         */
        protected function setPath($path = '', $plugin = null)
        {
            if (!$path && $plugin) {
                $path = $plugin->path . 'templates';
            }

            $this->path = $path;
        }

        /**
         * @param string|array $keyOrArray
         * @param mixed $value
         * 
         * @return self
         */
        public function attribute($keyOrArray, $value = null)
        {
            if (is_array($keyOrArray)) {
                $this->attributes = array_merge($this->attributes, $keyOrArray);
            } else {
                $this->attributes[$keyOrArray] = $value;
            }

            return $this;
        }

        /**
         * @param string $view
         * @param array $data
         * @param array $mergeData
         * 
         * @return string
         */
        public function render($view = null, $data = [], $mergeData = [])
        {
            $view = $this->resolvePath($view);
            $output = '';

            if (!is_file($view) && !is_readable($view)) {
                throw new \Exception('Invalid view file: ' . $view);
            }

            $data = array_merge($data, $mergeData);
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
            $viewPath = '';
            $paths = [
                get_stylesheet_directory() . '/' . $this->plugin->slug . '/templates',
                get_template_directory() . '/' . $this->plugin->slug . '/templates',
                $this->path,
            ];

            foreach (explode('.', $path) as $path) {
                $viewPath .= '/' . $path;
            }

            foreach ($paths as $path) {
                $view = $path . $viewPath . '.php';

                if (is_file($view) && is_readable($view)) {
                    $viewPath = $view;
                    break;
                }
            }

            return $viewPath;
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