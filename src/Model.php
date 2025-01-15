<?php

namespace WPTrait;

use WPTrait\Collection\{Cache,
    Email,
    Error,
    Event,
    Hooks,
    Log,
    Nonce,
    Option,
    Password,
    RestAPI,
    Route,
    Transient,
    View
};
use WPTrait\Data\Attachment;
use WPTrait\Data\Comment;
use WPTrait\Data\Post;
use WPTrait\Data\Term;
use WPTrait\Data\User;
use WPTrait\Http\HTTP;
use WPTrait\Http\Request;
use WPTrait\Http\Response;

if (!class_exists('WPTrait\Model')) {

    /**
     * @property Request $request { HTTP Request }
     * @property Response $response { HTTP Response }
     * @property HTTP $http { HTTP Client Request }
     * @property Constant $constant { WordPress Constant List }
     * @property Globals $global { WordPress Global Variables }
     * @property Url $url { WordPress Url Helper }
     * @property View $view { View and templates system }
     * @property Attachment $attachment { WordPress Attachment }
     * @property Filter $filter { WordPress Filter Hooks }
     * @property Action $action { WordPress Action Hooks }
     *
     */
    #[AllowDynamicProperties]
    class Model
    {
        use Hooks;

        /**
         * Get Plugin Data
         *
         * @var Information
         */
        public Information $plugin;

        /**
         * List Of WordPress Actions
         *
         * @var array
         */
        protected array $actions = [];

        /**
         * List Of WordPress Filters
         *
         * @var array
         */
        protected array $filters = [];

        /**
         * WordPress Database Class
         *
         * @var \WPDB
         */
        protected \wpdb $db;

        public $post, $term, $user, $option,
            $comment, $nonce, $transient, $cache, $event, $error, $rest, $log, $route,
            $email, $password;

        public function __construct(Information $plugin)
        {
            global $wpdb;

            // Set Plugin information
            $this->plugin = $plugin;

            // Set WordPress Database Class
            $this->db = $wpdb;

            # Setup Collection
            $this->post = new Post();
            $this->term = new Term();
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

            # Boot WordPress Hooks
            $this->bootHooks();
        }

        public function __get($name)
        {
            $class = [
                'request' => 'Http\Request',
                'response' => 'Http\Response',
                'http' => 'Http\HTTP',
                'constant' => 'Constant',
                'global' => 'Globals',
                'url' => 'Url',
                'filter' => 'Filter',
                'action' => 'Action',
                'attachment' => '\Collection\Attachment',
            ];

            // Setup view
            if ($name == "view") {
                $this->{$name} = new View($this->plugin);
            }

            // Check in Class
            if (array_key_exists($name, $class)) {
                $class_name = '\WPTrait\\' . $class[$name];
                $this->{$name} = new $class_name();
            }

            return $this->{$name};
        }

        private function bootHooks()
        {
            $this->bootTraitHooks();
            $this->bootVariableHooks();
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

        public function email($email)
        {
            return new Email($email);
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
                    $this->{$method}(isset($this->{$variable}) ? $this->{$variable} : $args);
                }
            }
        }

        private function bootVariableHooks()
        {
            foreach (['filters', 'actions'] as $hooks) {
                $hook = substr($hooks, 0, -1);
                if (is_array($this->{$hooks})) {
                    foreach ((array)$this->{$hooks} as $name => $args) {
                        if (is_array($args) && (!isset($args[1]) || !is_numeric($args[1]))) {
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
