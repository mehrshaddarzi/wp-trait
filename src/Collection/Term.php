<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('Term')) {

    trait Term
    {
        public $slug;

        public function get_terms($arg = array())
        {
            $default = array(
                'taxonomy' => $this->slug,
                'suppress_filter' => true,
                'orderby' => 'term_id',
                'order' => 'ASC',
                'parent' => '',
                'fields' => 'ids',
                'hide_empty' => false,
                /**
                 * @see https://developer.wordpress.org/reference/classes/wp_meta_query/#accepted-arguments
                 * 'meta_query' => array(
                 * array(
                 * 'key' => '',
                 * 'value' => '',
                 * 'compare' => '='
                 * )
                 * ),
                 */
            );
            $args = wp_parse_args($arg, $default);

            // Check Empty { is_array($query->terms) and count($get_terms->terms) > 0 && !is_wp_error($get_terms) }
            // Return { $query->terms (is Null When Empty) }
            return new \WP_Term_Query($args);
        }

        public function get_term($term_id)
        {
            return get_term($term_id);
        }

        public function add_term($name, $arg = array(), $taxonomy = null)
        {
            if (is_null($taxonomy)) {
                $taxonomy = $this->slug;
            }

            $default = array(
                'description' => '',
                'slug' => '',
                'parent' => 0,
            );
            $args = wp_parse_args($arg, $default);

            // (array('term_id'=>'','term_taxonomy_id'=>'') | WP_Error)
            return wp_insert_term(
                $name,
                $taxonomy,
                $args
            );
        }

        public function update_term($term_id, $arg = array(), $taxonomy = null)
        {
            if (is_null($taxonomy)) {
                $taxonomy = $this->slug;
            }

            // @see https://developer.wordpress.org/reference/functions/wp_update_term/#more-information
            $default = array(
                'name' => ''
            );
            $args = wp_parse_args($arg, $default);

            // (array('term_id'=>'','term_taxonomy_id'=>'') | WP_Error)
            return wp_update_term($term_id, $taxonomy, $args);
        }

        public function delete_term($term_id, $taxonomy = null)
        {
            # Delete all terms From taxonomy in WP-CLI
            # $ wp term list post_tag --field=term_id | xargs wp term delete post_tag

            if (is_null($taxonomy)) {
                $taxonomy = $this->slug;
            }
            return wp_delete_term($term_id, $taxonomy); //{ true or WP_Error }
        }

        public function get_term_meta($term_id, $meta_key, $single = false)
        {
            if (!$single) {
                return array_map(function ($a) {
                    return $a[0];
                }, get_term_meta($term_id));
            }

            return get_term_meta($term_id, $meta_key, true);
        }

        public function update_term_meta($term_id, $meta_key, $new_value)
        {
            update_term_meta($term_id, $meta_key, $new_value);
        }

        public function add_term_meta($term_id, $meta_key, $new_value)
        {
            add_term_meta($term_id, $meta_key, $new_value);
        }

        public function delete_term_meta($term_id, $meta_key)
        {
            return delete_term_meta($term_id, $meta_key);
        }

        public function term_exists($term, $taxonomy = '', $parent = null)
        {
            if (empty($taxonomy)) {
                $taxonomy = $this->slug;
            }
            return term_exists($term, $taxonomy, $parent);
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
    }

}