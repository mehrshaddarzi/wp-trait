<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\Term')) {

    class Term
    {
        /**
         * Taxonomy
         *
         * @var string
         */
        public $slug;

        /**
         * Term ID
         *
         * @var int
         */
        public $term_id;

        /**
         * Meta Class
         */
        public $meta;

        public function __construct($term_id = null, $slug = 'post_tag')
        {
            $this->term_id = $term_id;
            $this->slug = $slug;
            $this->meta = new Meta('term', $this->term_id);
        }

        public function get($term_id = null)
        {
            return get_term((is_null($term_id) ? $this->term_id : $term_id));
        }

        public function delete($term_id = null, $taxonomy = null)
        {
            # Delete all terms From taxonomy in WP-CLI
            # $ wp term list post_tag --field=term_id | xargs wp term delete post_tag

            # True or WP_Error
            $term_id = (is_null($term_id) ? $this->term_id : $term_id);
            return wp_delete_term($term_id, (is_null($taxonomy) ? $this->get($term_id)->taxonomy : $taxonomy));
        }

        public function exists($term_id = null, $taxonomy = '', $parent = null)
        {
            return term_exists((is_null($term_id) ? $this->term_id : $term_id), $taxonomy, $parent);
        }

        public function add($name, $arg = array(), $taxonomy = null)
        {
            $default = array(
                'description' => '',
                'slug' => '',
                'parent' => 0,
            );
            $args = wp_parse_args($arg, $default);

            # (array('term_id'=>'','term_taxonomy_id'=>'') | WP_Error)
            return wp_insert_term(
                $name,
                (is_null($taxonomy) ? $this->slug : $taxonomy),
                $args
            );
        }

        public function update($arg = array(), $taxonomy = null, $term_id = null)
        {
            # @see https://developer.wordpress.org/reference/functions/wp_update_term
            $default = array(
                'name' => ''
            );
            $args = wp_parse_args($arg, $default);

            # (array('term_id'=>'','term_taxonomy_id'=>'') | WP_Error)
            return wp_update_term((is_null($term_id) ? $this->term_id : $term_id), (is_null($taxonomy) ? $this->get($term_id)->taxonomy : $taxonomy), $args);
        }

        public function query($arg = array())
        {
            # Cache
            if (isset($arg['cache']) and $arg['cache'] === false) {
                $arg['update_term_meta_cache'] = false;
                unset($arg['cache']);
            }

            # Alias
            $alias = array(
                'return' => 'fields',
                'meta' => 'meta_query',
                'tax' => 'taxonomy'
            );
            $arg = $this->convertAliasArg($arg, $alias);

            # Check Return only ids
            if (isset($arg['fields']) and in_array($arg['fields'], array('id', 'ids', 'ID'))) {
                $arg['fields'] = 'ids';
            }

            # Sanitize Meta Query
            if (isset($arg['meta_query']) and !isset($arg['meta_query'][0])) {
                $arg['meta_query'] = [$arg['meta_query']];
            }

            # Default Params
            $default = array(
                'taxonomy' => $this->slug,
                'orderby' => 'term_id',
                'order' => 'ASC',
                'parent' => '',
                'hide_empty' => false
            );
            $args = wp_parse_args($arg, $default);
            return new \WP_Term_Query($args);
        }

        public function list($arg = array())
        {
            return $this->query($arg)->get_terms();
        }

        public function toSql($arg = array())
        {
            return $this->query($arg)->request;
        }

        public function get_taxonomy($taxonomy = null)
        {
            if (is_null($taxonomy)) {
                $taxonomy = $this->slug;
            }

            return get_taxonomy($taxonomy);
        }

        public function get_taxonomies($args = array(), $output = 'objects', $operator = 'and')
        {
            return get_taxonomies($args, $output, $operator);
        }

        public function sort_terms_hierarchically(array $terms, $parentId = 0)
        {
            $into = array();
            foreach ($terms as $i => $term) {
                if ($term->parent == $parentId) {
                    $term->children = $this->sort_terms_hierarchically($terms, $term->term_id);
                    $into[$term->term_id] = $term;
                }
            }

            return $into;
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
