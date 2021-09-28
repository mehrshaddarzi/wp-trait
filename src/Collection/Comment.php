<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Comment')) {

    class Comment
    {
        /**
         * Comment ID
         *
         * @var int
         */
        public $id;

        /**
         * Meta Class
         */
        public $meta;

        public function __construct($id = null)
        {
            $this->id = $id;
            $this->meta = new Meta('comment', $this->id);
        }

        public function get($id = null)
        {
            return get_comment((is_null($id) ? $this->id : $id));
        }

        public function delete($force_delete = false, $id = null)
        {
            return wp_delete_comment($force_delete, (is_null($id) ? $this->id : $id));
        }

        public function add($arg = array())
        {
            $default = array(
                'content' => '',
                'post_id' => 0,
                'type' => 'comment',
                'name' => '',
                'email' => ''
            );
            $args = wp_parse_args($arg, $default);

            # (int|false) The new comment's ID on success, false on failure.
            return wp_insert_comment($this->convertAliasArg($args));
        }

        public function update($arg = array())
        {
            $default = array(
                'id' => $this->id
            );
            $args = wp_parse_args($arg, $default);

            # (int|false|WP_Error)
            return wp_update_comment($this->convertAliasArg($args));
        }

        public function list($arg = array())
        {
            # Cache
            if (isset($arg['cache']) and $arg['cache'] === false) {
                $arg['update_comment_meta_cache'] = false;
                $arg['update_comment_post_cache'] = false;
                unset($arg['cache']);
            }

            # alias
            $alias = array(
                'return' => 'fields',
                'meta' => 'meta_query',
                'date' => 'date_query',
                'email' => 'author_email',
                'url' => 'author_url'
            );
            $arg = $this->convertAliasArg($arg, $alias);

            # Nested
            if (isset($arg['nested']) and $arg['nested'] === true) {
                $arg['hierarchical'] = 'threaded';
                unset($arg['nested']);
            }

            # Check Return only ids
            if (isset($arg['fields']) and in_array($arg['fields'], array('id', 'ids', 'ID'))) {
                $arg['fields'] = 'ids';
            }

            # Default Params
            $default = array(
                'count' => false,
                'hierarchical' => false
            );
            $args = wp_parse_args($arg, $default);
            $query = new \WP_Comment_Query;
            return $query->query($args);
        }

        private function aliasArgument()
        {
            return array(
                'id' => 'comment_ID',
                'ID' => 'comment_ID',
                'agent' => 'comment_agent',
                'approved' => 'comment_approved',
                'author' => 'comment_author',
                'name' => 'comment_author',
                'author_email' => 'comment_author_email',
                'email' => 'comment_author_email',
                'ip' => 'comment_author_IP',
                'url' => 'comment_author_url',
                'site' => 'comment_author_url',
                'content' => 'comment_content',
                'date' => 'comment_date',
                'date_gmt' => 'comment_date_gmt',
                'karma' => 'comment_karma',
                'parent' => 'comment_parent',
                'parent_id' => 'comment_parent',
                'post_ID' => 'comment_post_ID',
                'post_id' => 'comment_post_ID',
                'type' => 'comment_type',
                'meta' => 'comment_meta'
            );
        }

        private function convertAliasArg($array = array(), $alias = null)
        {
            $_array = array();
            $alias = (is_null($alias) ? $this->aliasArgument() : $alias);
            foreach ($array as $key => $value) {
                $_array[(isset($alias[$key]) ? $alias[$key] : $key)] = $value;
            }

            return $_array;
        }
    }

}
