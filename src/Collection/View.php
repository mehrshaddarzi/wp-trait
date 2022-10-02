<?php

namespace WPTrait\Collection;

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
		 * @param string $path
		 */
		public function __construct($path = '')
		{
			$this->attributes = [];
			$this->set_path($path);
		}

		/**
		 * @param string $path
		 * 
		 * @return void
		 */
		protected function set_path($path)
		{
			$this->path = $path ?: $this->constant('plugin_dir') . '/templates';
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
			$output = '';

			if (!is_file($view) && !is_readable($view)) {
				return $output;
			}

			$view = $this->resolvePath($view);
			$data = array_merge($data, $merge_data);
			$data = array_merge($data, $this->attributes);

			try {
				ob_start();

				if ($data) {
					extract($data);
				}

				include $this->path . '/' . $view . '.php';

				$output = ob_get_clean();
			} catch (\Exception $e) {
				ob_end_clean();
				$output = '';
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

			return $view_path;
		}

		public function __set($name, $value)
		{
			$this->attributes[$name] = $value;
		}

		public function __invoke($view = null, $data = [], $merge_data = [])
		{
			return $this->render($view, $data, $merge_data);
		}
	}
}