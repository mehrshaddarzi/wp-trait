<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\File')) {

    class File
    {
        /**
         * File Path
         *
         * @var string
         */
        public $file;

        private $wp_filesystem;

        public function __construct($file = null)
        {
            $this->file = $file;
            $this->wp_filesystem = $this->WP_FileSystem();
        }

        public function exists($file = null)
        {
            return $this->wp_filesystem->exists((is_null($file) ? $this->file : $file));
        }

        public function missing($file = null)
        {
            return !$this->exists((is_null($file) ? $this->file : $file));
        }

        public function get($file = null)
        {
            return $this->wp_filesystem->get_contents((is_null($file) ? $this->file : $file));
        }

        public function put($contents, $mode = false, $file = null)
        {
            return $this->wp_filesystem->put_contents((is_null($file) ? $this->file : $file), $contents, $mode);
        }

        public function create(...$arg)
        {
            return $this->put(...$arg);
        }

        public function prepend($data, $path = null)
        {
            $path = (is_null($path) ? $this->file : $path);
            if ($this->exists($path)) {
                return $this->put($data . $this->get($path), $path);
            }

            return $this->put($data, $path);
        }

        public function append($data, $path = null)
        {
            return @file_put_contents((is_null($path) ? $this->file : $path), $data, FILE_APPEND);
        }

        public function mkdir($path, $chmod = null)
        {
            return @mkdir($path, (is_null($chmod) ? FS_CHMOD_DIR : $chmod), true);
        }

        public function chmod($mode = false, $recursive = false, $file = null)
        {
            return $this->wp_filesystem->chmod((is_null($file) ? $this->file : $file), $mode, $recursive);
        }

        public function copy($destination, $overwrite = false, $mode = false, $source = null)
        {
            $path = $this->prepareDestination($source, $destination);
            return $this->wp_filesystem->copy($path->source, $path->destination, $overwrite, $mode);
        }

        public function move($destination, $overwrite = false, $source = null)
        {
            $path = $this->prepareDestination($source, $destination);
            return $this->wp_filesystem->move($path->source, $path->destination, $overwrite);
        }

        public function delete($recursive = false, $type = false, $file = null)
        {
            return $this->wp_filesystem->delete((is_null($file) ? $this->file : $file), $recursive, $type);
        }

        public function isFile($path = null)
        {
            return $this->wp_filesystem->is_file((is_null($path) ? $this->file : $path));
        }

        public function isDir($path = null)
        {
            return $this->wp_filesystem->is_dir((is_null($path) ? $this->file : $path));
        }

        public function isReadable($path = null)
        {
            return @is_readable((is_null($path) ? $this->file : $path));
        }

        public function isWritable($path = null)
        {
            return @is_writable((is_null($path) ? $this->file : $path));
        }

        public function lastModified($path = null)
        {
            return @filemtime((is_null($path) ? $this->file : $path));
        }

        public function size($path = null)
        {
            return @filesize((is_null($path) ? $this->file : $path));
        }

        public function info($path = null)
        {
            return pathinfo((is_null($path) ? $this->file : $path));
        }

        public function name($path = null)
        {
            return pathinfo((is_null($path) ? $this->file : $path), PATHINFO_FILENAME);
        }

        public function basename($path = null)
        {
            return pathinfo((is_null($path) ? $this->file : $path), PATHINFO_BASENAME);
        }

        public function dirname($path = null)
        {
            return pathinfo((is_null($path) ? $this->file : $path), PATHINFO_DIRNAME);
        }

        public function extension($path = null)
        {
            return pathinfo((is_null($path) ? $this->file : $path), PATHINFO_EXTENSION);
        }

        private function prepareDestination($source = null, $destination = '')
        {
            $source = (is_null($source) ? $this->file : $source);
            $destination = ($this->isDir($destination) ? trailingslashit($destination) . $this->basename($source) : $destination);
            $this->autoCreateDir($destination);

            return (object)['source' => $source, 'destination' => $destination];
        }

        private function autoCreateDir($dir)
        {
            $path = $this->dirname($dir);
            if (!$this->exists($path)) {
                $this->mkdir($path);
            }
        }

        private function WP_FileSystem()
        {
            if (isset($GLOBALS['_wp_filesystem_direct_method'])) {
                return $GLOBALS['wp_filesystem'];
            }

            require_once(ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
            return $GLOBALS['wp_filesystem'];
        }

    }

}