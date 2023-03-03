<?php

namespace WPTrait;

use WPTrait\Collection\{
    Action,
    Attachment,
    Cache,
    Comment,
    Cookie,
    Error,
    Event,
    Email,
    File,
    Filter,
    Hooks,
    Log,
    Nonce,
    Option,
    Password,
    Post,
    Request,
    Response,
    RestAPI,
    Route,
    Session,
    Term,
    Transient,
    User,
    View
};

if (!class_exists('WPTrait\Model')) {

    class Model
    {
        use Hooks;

        /**
         * Get Plugin Data
         *
         * @var Information
         */
        public $plugin;

        /**
         * View and templates system
         *
         * @var View
         */
        public $view;

        /**
         * List Of WordPress Actions
         *
         * @var array
         */
        protected $actions = [];

        /**
         * List Of WordPress Filters
         *
         * @var array
         */
        protected $filters = [];

        /**
         * Get REQUEST data
         *
         * @var Request
         */
        public $request;

        /**
         * HTTP Response
         *
         * @var Response
         */
        public $response;

        /**
         * WordPress Constant List
         *
         * @var Constant
         */
        public $constant;

        public $db, $wp, $pagenow, $admin_bar, $screen, $post, $term, $attachment, $user, $option,
            $comment, $nonce, $transient, $cache, $event, $error, $rest, $log, $route, $filter, $action,
            $cookie, $session, $file, $email, $password;

        public function __construct(Information $plugin)
        {
            global $wpdb, $wp, $pagenow, $wp_admin_bar, $current_screen;

            # @see https://codex.wordpress.org/Global_Variables
            $this->db = $wpdb;
            $this->wp = $wp;
            $this->pagenow = $pagenow;
            $this->admin_bar = $wp_admin_bar;
            $this->screen = $current_screen;

            # Set Plugin information
            $this->plugin = $plugin;

            # Setup Collection
            $this->post = new Post();
            $this->term = new Term();
            $this->attachment = new Attachment();
            $this->user = new User();
            $this->password = new Password();
            $this->option = new Option();
            $this->comment = new Comment();
            $this->nonce = new Nonce();
            $this->transient = new Transient();
            $this->cache = new Cache();
            $this->event = new Event();
            $this->error = new Error();
            $this->email = new Email();
            $this->rest = new RestAPI();
            $this->log = new Log();
            $this->route = new Route();
            $this->filter = new Filter();
            $this->action = new Action();
            $this->cookie = new Cookie();
            $this->session = new Session();
            $this->file = new File();

            # Boot WordPress Hooks
            $this->bootHooks();
        }

        public function __get($name)
        {
            switch ($name) {
                case "view":
                    $this->{$name} = new View($this->plugin);
                    break;
                case "request":
                    $this->{$name} = new Request();
                    break;
                case "response":
                    $this->{$name} = new Response();
                    break;
                case "constant":
                    $this->{$name} = new Constant();
                    break;
            }

            return $this->{$name};
        }

        public function bootHooks()
        {
            $this->bootTraitHooks();
            $this->bootVariableHooks();
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

        public function cookie($name)
        {
            return new Cookie($name);
        }

        public function session($name)
        {
            return new Session($name);
        }

        public function nonce($action)
        {
            return new Nonce($action);
        }

        public function transient($name)
        {
            return new Transient($name);
        }

        public function email($email)
        {
            return new Email($email);
        }

        public function file($file)
        {
            return new File($file);
        }

        public function log($log = '', $type = 'debug', $condition = null)
        {
            return $this->log->add($log, $type, $condition);
        }

        private function bootTraitHooks()
        {
            $booted = [];
            $Trait = array_keys($this->getUsedTraits($this));
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
                $hook = substr($hooks, 0, -1);
                if (is_array($this->{$hooks})) {
                    foreach ((array)$this->{$hooks} as $name => $args) {
                        if (is_array($args) and !is_numeric($args[1])) {
                            foreach ($args as $method) {
                                $this->runVariableHooks($hook, $name, $method);
                            }
                        } else {
                            $this->runVariableHooks($hook, $name, $args);
                        }
                    }
                }
            }
        }

        private function runVariableHooks($type, $name, $args)
        {
            $function = (is_array($args) ? $args[0] : ((is_bool($args) ? ($args === true ? '__return_true' : '__return_false') : $args)));
            $priority = (is_array($args) ? (isset($args[1]) ? $args[1] : 10) : 10);
            $accepted_args = (is_array($args) ? (isset($args[2]) ? $args[2] : 1) : 1);
            $this->{$type}->add($name, (in_array($function, array('__return_false', '__return_true')) ? $function : [$this, $function]), $priority, $accepted_args);
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
