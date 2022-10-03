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
         * @param bool $canOverride
         * 
         * @return string
         */
        public function render($view = null, $data = [], $mergeData = [], $canOverride = true)
        {
            $view = $this->resolvePath($view, $canOverride);
            $output = '';

            if (!is_file($view) && !is_readable($view)) {
                wp_die(sprintf('View file "%s" does not exist or is not a readable file.', $view));
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
         * @param bool $canOverride
         * 
         * @return string
         */
        protected function resolvePath($path, $canOverride = true)
        {
            $viewPath = '';
            $paths = [
                get_stylesheet_directory() . '/' . $this->plugin->slug,
                get_template_directory() . '/' . $this->plugin->slug,
            ];

            foreach (explode('.', $path) as $path) {
                $viewPath .= '/' . $path;
            }

            $defaultView = $this->path . $viewPath . '.php';

            if (!$canOverride) {
                return $defaultView;
            }
            
            foreach ($paths as $path) {
                $view = $path . $viewPath . '.php';

                if (is_file($view) && is_readable($view)) {
                    $viewPath = $view;
                    break;
                }

                $viewPath = $defaultView; # Fallback to default view path
            }

            return $viewPath;
        }

        public function __set($name, $value)
        {
            $this->attributes[$name] = $value;
        }

        public function __invoke($view = null, $data = [], $mergeData = [], $canOverride = true)
        {
            return $this->render($view, $data, $mergeData, $canOverride);
        }
    }
}