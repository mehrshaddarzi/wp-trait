<?php

namespace WPTrait;

use WPTrait\Hook\Constant;
use WPTrait\Collection\{
    Action,
    Attachment,
    Cache,
    Comment,
    Error,
    Event,
    Filter,
    Hooks,
    Log,
    Nonce,
    Option,
    Post,
    Request,
    RestAPI,
    Route,
    Term,
    Transient,
    User
};

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Model')) {

    class Model
    {
        use Hooks, Constant;

        public $db, $wp, $plugin, $pagenow, $post, $term, $attachment, $user, $option, $request, $comment, $nonce, $transient, $cache, $event, $error, $rest, $log, $route, $filter, $action;

        protected $actions, $filters = [];

        public function __construct($plugin = [])
        {
            # @see https://codex.wordpress.org/Global_Variables
            $this->db = $GLOBALS['wpdb'];
            $this->wp = $GLOBALS['wp'];
            $this->pagenow = $GLOBALS['pagenow'];

            # Set Plugin information
            $this->plugin = $plugin;

            # Setup Collection
            $collection = [
                'post' => Post::class,
                'term' => Term::class,
                'attachment' => Attachment::class,
                'user' => User::class,
                'option' => Option::class,
                'request' => Request::class,
                'comment' => Comment::class,
                'nonce' => Nonce::class,
                'transient' => Transient::class,
                'cache' => Cache::class,
                'event' => Event::class,
                'error' => Error::class,
                'rest' => RestAPI::class,
                'log' => Log::class,
                'route' => Route::class,
                'filter' => Filter::class,
                'action' => Action::class
            ];
            foreach ($collection as $variable => $class) {
                $this->{$variable} = new $class();
            }

            # Boot WordPress Hooks
            $this->bootHooks();
        }

        public function bootHooks()
        {
            $this->bootTraitHooks();
            $this->bootVariableHooks();
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

        public function log($log = '', $type = 'debug', $condition = null)
        {
            return $this->log->add($log, $type, $condition);
        }

        private function bootTraitHooks()
        {
            $booted = [];
            $Trait = (array)array_keys($this->getUsedTraits($this));
            foreach ($Trait as $trait) {
                $basename = basename(str_replace('\\', '/', $trait));
                $method = 'boot' . $basename;
                $args = [];
                if (method_exists($trait, $method) && !in_array($method, $booted)) {
                    $booted[] = $method;
                    $variable = lcfirst($basename);
                    $this->{$method}((isset($this->{$variable}) ? $this->{$variable} : $args));
                }
            }
        }

        private function bootVariableHooks()
        {
            foreach (['filters', 'actions'] as $hooks) {
                foreach ($this->{$hooks} as $name => $args) {
                    $this->runVariableHooks(substr($hooks, 0, -1), $name, $args);
                }
            }
        }

        private function runVariableHooks($type, $name, $args)
        {
            $function = (is_array($args) ? $args[0] : $args);
            $priority = (is_array($args) ? (isset($args[1]) ? $args[1] : 10) : 10);
            $accepted_args = (is_array($args) ? (isset($args[2]) ? $args[2] : 1) : 1);
            $this->{$type}->add($name, [__CLASS__, $function], $priority, $accepted_args);
        }

        private function getUsedTraits($classInstance)
        {
            $parentClasses = class_parents($classInstance);
            $traits = class_uses($classInstance);

            foreach ($parentClasses as $parentClass) {
                $traits = array_merge($traits, class_uses($parentClass));
            }

            return $traits;
        }
    }

}
