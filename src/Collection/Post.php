<?php

namespace WPTrait\Collection;

use WPTrait\Utils\Arr;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\Post')) {

    class Post
    {
        /**
         * Post Type
         *
         * @var string
         */
        public $slug;

        /**
         * Post ID
         *
         * @var int
         */
        public $post_id;

        /**
         * Meta Class
         */
        public $meta;

        /**
         * Alias Argument in insert/update Post
         */
        public $alias = [
            'id' => 'ID',
            'user' => 'post_author',
            'author' => 'post_author',
            'title' => 'post_title',
            'date' => 'post_date',
            'date_gmt' => 'post_date_gmt',
            'content' => 'post_content',
            'content_filtered' => 'post_content_filtered',
            'excerpt' => 'post_excerpt',
            'status' => 'post_status',
            'type' => 'post_type',
            'name' => 'post_name',
            'modified' => 'post_modified',
            'modified_gmt' => 'post_modified_gmt',
            'parent' => 'post_parent',
            'parent_id' => 'post_parent',
            'mime' => 'post_mime_type',
            'mime_type' => 'post_mime_type',
            'category' => 'post_category',
            'tags' => 'tags_input',
            'tag' => 'tags_input',
            'tax' => 'tax_input',
            'meta' => 'meta_input'
        ];

        public function __construct($post_id = null, $slug = 'post')
        {
            $this->post_id = $post_id;
            $this->slug = $slug;
            $this->meta = new Meta('post', $this->post_id);
        }

        public function get($post_id = null, $output = OBJECT)
        {
            return get_post((is_null($post_id) ? $this->post_id : $post_id), $output);
        }

        public function delete($post_id = null, $force = false)
        {
            # (WP_Post|false|null) Post data on success, false or null on failure.
            return wp_delete_post((is_null($post_id) ? $this->post_id : $post_id), $force);
        }

        public function add($arg = [])
        {
            # Generate Alias Argument
            $default = [
                'title' => '',
                'date' => current_time('mysql'),
                'type' => $this->slug,
                'status' => 'publish'
            ];
            $args = wp_parse_args($arg, $default);

            # (int|WP_Error) The post ID on success. The value 0 or WP_Error on failure.
            return wp_insert_post(Arr::alias($args, $this->alias));
        }

        public function update($arg = [])
        {
            # Default
            $default = [
                'id' => $this->post_id
            ];
            $args = wp_parse_args($arg, $default);

            # (int|WP_Error) The post ID on success. The value 0 or WP_Error on failure.
            return wp_update_post(Arr::alias($args, $this->alias));
        }

        public function permalink($post_id = null, $leave_name = false)
        {
            return get_the_permalink((is_null($post_id) ? $this->post_id : $post_id), $leave_name);
        }

        public function shortlink($post_id = null, $context = 'post', $allow_slugs = true)
        {
            return wp_get_shortlink((is_null($post_id) ? $this->post_id : $post_id), $context, $allow_slugs);
        }

        public function thumbnail($post_id = null)
        {
            $thumbnail_id = get_post_thumbnail_id((is_null($post_id) ? $this->post_id : $post_id));
            if (!$thumbnail_id) {
                return false;
            }

            return new Attachment($thumbnail_id);
        }

        public function has_thumbnail($post_id = null)
        {
            return has_post_thumbnail((is_null($post_id) ? $this->post_id : $post_id));
        }

        public function exists($post_id = null)
        {
            $post_id = (is_null($post_id) ? $this->post_id : $post_id);
            if (absint($post_id) < 1) {
                return false;
            }
            return !is_null($this->get($post_id, 'raw'));
        }

        public function terms($taxonomy = 'post_tag', $args = ['fields' => 'all'], $post_id = null)
        {
            return wp_get_post_terms((is_null($post_id) ? $this->post_id : $post_id), $taxonomy, $args);
        }

        public function collection($meta = [], $taxonomy = [], $post_id = null)
        {
            $post_id = (is_null($post_id) ? $this->post_id : $post_id);
            $post_object = $this->get($post_id, OBJECT);
            if (is_null($post_object)) {
                return null;
            }

            // Check Meta
            if ($meta == "all") {
                $post_object->meta = (object)$this->meta->all($post_id);
            } elseif (is_array($meta) and !empty($meta)) {
                foreach ($meta as $meta_key) {
                    $post_object->meta->{$meta_key} = $this->meta->get($meta_key, $post_id);
                }
            }

            // Check Taxonomy
            if (!empty($taxonomy)) {
                foreach ($taxonomy as $tax) {
                    if (taxonomy_exists($tax)) {
                        $post_object->{$tax} = $this->terms($tax, [], $post_id);
                    }
                }
            }

            return $post_object;
        }

        public function edit_post_link($post_id = null)
        {
            return get_edit_post_link((is_null($post_id) ? $this->post_id : $post_id), 'display');
        }

        public function query($arg = [])
        {
            # alias
            $alias = [
                'id' => 'p',
                'user' => 'author',
                'category' => 'cat',
                'type' => 'post_type',
                'status' => 'post_status',
                'per_page' => 'posts_per_page',
                'page' => 'paged',
                'order_by' => 'orderby',
                'meta' => 'meta_query',
                'date' => 'date_query',
                'tax' => 'tax_query',
                'mime_type ' => 'post_mime_type',
                'return' => 'fields'
            ];
            $arg = Arr::alias($arg, $alias);

            # Check Return only ids
            if (isset($arg['fields'])) {
                $arg['fields'] = ((is_array($arg['fields']) and count($arg['fields']) == 1) ? $arg['fields'][0] : $arg['fields']);
                if (is_string($arg['fields']) and in_array($arg['fields'], ['id', 'ids', 'ID'])) {
                    $arg['fields'] = 'ids';
                }
            }

            # Cache Result
            if (isset($arg['cache']) and $arg['cache'] === false) {
                $arg = array_merge(
                    $arg,
                    [
                        'cache_results' => false,
                        'no_found_rows' => true, #@see https://10up.github.io/Engineering-Best-Practices/php/#performance
                        'update_post_meta_cache' => false,
                        'update_post_term_cache' => false,
                    ]
                );
                unset($arg['cache']);
            }

            # Suppress filters
            if (isset($arg['filter']) and $arg['filter'] === false) {
                $arg['suppress_filters'] = true;
                unset($arg['filter']);
            }

            # Sanitize Meta Query
            if (isset($arg['meta_query']) and !isset($arg['meta_query'][0])) {
                $arg['meta_query'] = [$arg['meta_query']];
            }

            # Default Params
            $default = [
                'post_type' => $this->slug,
                'post_status' => 'publish',
                'posts_per_page' => '-1',
                'order' => 'DESC'
            ];
            $args = wp_parse_args($arg, $default);

            # Return { $query->posts }
            # Get SQL { $query->request }
            # Check Exists { $query->have_posts() }
            return new \WP_Query($args);
        }

        public function list($arg = [])
        {
            return $this->query($arg)->posts;
        }

        public function toSql($arg = [])
        {
            return $this->query($arg)->request;
        }

        public function global()
        {
            return $GLOBALS['post'];
        }

        public function comments($args = [], $post_id = null)
        {
            $comment = new Comment();
            return $comment->list(array_merge(array('post_id' => (is_null($post_id) ? $this->post_id : $post_id), $args)));
        }

        public function get_post_types($args = [], $output = 'objects', $operator = 'and')
        {
            return get_post_types($args, $output, $operator);
        }

        public function in_edit_page($new_edit = null)
        {
            # global $typenow; (is_edit_page('new') and $typenow =="POST_TYPE")
            global $pagenow;
            if (!is_admin()) return false;
            if ($new_edit == "edit")
                return in_array($pagenow, ['post.php']);
            elseif ($new_edit == "new")
                return in_array($pagenow, ['post-new.php']);
            else
                return in_array($pagenow, ['post.php', 'post-new.php']);
        }

    }

}
