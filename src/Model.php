<?php

namespace WPTrait;

use WPTrait\Collection\Attachment;
use WPTrait\Collection\Cache;
use WPTrait\Collection\Comment;
use WPTrait\Collection\Event;
use WPTrait\Collection\Hooks;
use WPTrait\Collection\Nonce;
use WPTrait\Collection\Option;
use WPTrait\Collection\Post;
use WPTrait\Collection\Request;
use WPTrait\Collection\Term;
use WPTrait\Collection\Transient;
use WPTrait\Collection\User;
use WPTrait\Hook\Constant;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Model')) {

    class Model
    {
        use Hooks, Constant;

        public $db, $wp, $plugin, $pagenow, $post, $term, $attachment, $user, $option, $request, $comment, $nonce, $transient, $cache, $event;

        public function __construct($plugin = array())
        {
            # @see https://codex.wordpress.org/Global_Variables
            $this->db = $GLOBALS['wpdb'];
            $this->wp = $GLOBALS['wp'];
            $this->pagenow = $GLOBALS['pagenow'];

            # Set Plugin information
            $this->plugin = $plugin;

            # Setup Collection
            $this->post = new Post();
            $this->term = new Term();
            $this->attachment = new Attachment();
            $this->user = new User();
            $this->option = new Option();
            $this->request = new Request();
            $this->comment = new Comment();
            $this->nonce = new Nonce();
            $this->transient = new Transient();
            $this->cache = new Cache();
            $this->event = new Event();

            # Boot WordPress Hooks
            $this->bootHooks();
        }

        public function bootHooks()
        {
            $booted = array();
            $Trait = (array)array_keys(class_uses($this));
            foreach ($Trait as $trait) {
                $basename = basename(str_replace('\\', '/', $trait));
                $method = 'boot' . $basename;
                $args = array();
                if (method_exists($this, $method) && !in_array($method, $booted)) {
                    $booted[] = $method;
                    $variable = lcfirst($basename);
                    $this->{$method}((isset($this->{$variable}) ? $this->{$variable} : $args));
                }
            }
        }

        public function getFile($path = '', $type = 'url')
        {
            return rtrim($this->plugin->{$type}, '/') . '/' . ltrim($path, '/');
        }

        public function getFileUrl($path = '')
        {
            return $this->getFile($path, 'url');
        }

        public function getFilePath($path = '')
        {
            return $this->getFile($path, 'path');
        }

        public function post($post_id)
        {
            return new Post($post_id);
        }

        public function comment($comment_id)
        {
            return new Comment($comment_id);
        }

        public function term($term_id)
        {
            return new Term($term_id);
        }

        public function attachment($attachment_id)
        {
            return new Attachment($attachment_id);
        }

        public function user($user_id)
        {
            return new User($user_id);
        }

        public function option($name)
        {
            return new Option($name);
        }

        public function nonce($action)
        {
            return new Nonce($action);
        }

        public function transient($name)
        {
            return new Transient($name);
        }
    }

}
