<?php

namespace WPTrait\Data;

use WPTrait\Abstracts\Data;
use WPTrait\Utils\Arr;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Data\Post')) {

    class Post extends Data
    {

        /**
         * Post Author ID
         *
         * @var int|null
         */
        public int|null $author = null;

        /**
         * Post Date
         *
         * @var string
         */
        public string $date = '';

        /**
         * Post Date GMT
         *
         * @var string
         */
        public string $date_gmt = '';

        /**
         * Post Modified Date
         *
         * @var string
         */
        public string $modified = '';

        /**
         * Post Modified Date GMT
         *
         * @var string
         */
        public string $modified_gmt = '';

        /**
         * Post Content
         *
         * @var string
         */
        public string $content = '';

        /**
         * Post Content Filtered
         *
         * @var string
         */
        public string $content_filtered = '';

        /**
         * Post Title
         *
         * @var string
         */
        public string $title = '';

        /**
         * Post Excerpt
         *
         * @var string
         */
        public string $excerpt = '';

        /**
         * Post Status
         *
         * @var string
         */
        public string $status = 'draft';

        /**
         * Post Type
         *
         * @var string
         */
        public string $type = 'post';

        /**
         * Post Comment Status
         *
         * @var string
         */
        public string $comment_status = '';

        /**
         * Post Ping Status
         *
         * @var string
         */
        public string $ping_status = '';

        /**
         * Post Password
         *
         * @var string
         */
        public string $password = '';

        /**
         * Post Name
         *
         * @var string
         */
        public string $slug = '';

        /**
         * Post Parent ID
         *
         * @var int
         */
        public int $parent = 0;

        /**
         * Post Menu Order
         *
         * @var int
         */
        public int $menu_order = 0;

        /**
         * Post MIME Type
         *
         * @var string
         */
        public string $mime_type = '';

        /**
         * Global Unique ID for referencing the post
         *
         * @var string
         */
        public string $guid = '';

        /**
         * Number of comments on post
         *
         * @var int
         */
        public int $comment_count = 0;

        /**
         * Page template to use.
         *
         * @var string
         */
        public string $template = '';

        public function __construct($id = 0)
        {
            parent::__construct($id, 'post');
        }

        public function author($author_id): static
        {
            $this->author = $author_id;
            return $this;
        }

        public function date($date): static
        {
            $this->date = $date;
            $this->changed('date');
            return $this;
        }

        public function date_gmt($date_gmt): static
        {
            $this->date_gmt = $date_gmt;
            $this->changed('date_gmt');
            return $this;
        }

        public function modified($modified): static
        {
            $this->modified = $modified;
            $this->changed('modified');
            return $this;
        }

        public function modified_gmt($modified_gmt): static
        {
            $this->modified_gmt = $modified_gmt;
            $this->changed('modified_gmt');
            return $this;
        }

        public function content($content): static
        {
            $this->content = $content;
            return $this;
        }

        public function content_filtered($content_filtered): static
        {
            $this->content_filtered = $content_filtered;
            return $this;
        }

        public function title($title): static
        {
            $this->title = $title;
            return $this;
        }

        public function excerpt($excerpt): static
        {
            $this->excerpt = $excerpt;
            return $this;
        }

        public function status($status): static
        {
            $this->status = $status;
            return $this;
        }

        public function type($type): static
        {
            $this->type = $type;
            return $this;
        }

        public function comment_status($comment_status): static
        {
            if (is_bool($comment_status)) {
                $comment_status = ($comment_status === true ? 'open' : 'closed');
            }
            $this->comment_status = $comment_status;
            $this->changed('comment_status');
            return $this;
        }

        public function ping_status($ping_status): static
        {
            if (is_bool($ping_status)) {
                $ping_status = ($ping_status === true ? 'open' : 'closed');
            }
            $this->ping_status = $ping_status;
            $this->changed('ping_status');
            return $this;
        }

        public function password($password): static
        {
            $this->password = $password;
            return $this;
        }

        public function slug($slug): static
        {
            $this->slug = $slug;
            return $this;
        }

        public function parent($parent): static
        {
            $this->parent = $parent;
            return $this;
        }

        public function menu_order($menu_order): static
        {
            $this->menu_order = $menu_order;
            return $this;
        }

        public function mime_type($mime): static
        {
            $this->mime_type = $mime;
            return $this;
        }

        public function guid($guid): static
        {
            $this->guid = $guid;
            return $this;
        }

        public function template($page_template): static
        {
            $this->template = $page_template;
            return $this;
        }

        public function setParams(): static
        {
            // Init
            $this->params = [
                'post_content' => $this->content,
                'post_content_filtered' => $this->content_filtered,
                'post_title' => $this->title,
                'post_excerpt' => $this->excerpt,
                'post_status' => $this->status,
                'post_password' => $this->password,
                'post_name' => $this->slug,
                'post_parent' => $this->parent,
                'menu_order' => $this->menu_order,
                'post_mime_type' => $this->mime_type,
                'guid' => $this->guid,
                'post_type' => $this->type
            ];

            // ID
            if ($this->id > 0) {
                $this->params['ID'] = $this->id;
            }

            // post_author
            if (is_int($this->author) and $this->author > 0) {
                $this->params['post_author'] = $this->author;
            }

            // post_date
            if (!empty($this->date) and $this->wasChanged('date')) {
                $this->params['post_date'] = $this->date;
            }

            // post_date_gmt
            if (!empty($this->date_gmt) and $this->wasChanged('date_gmt')) {
                $this->params['post_date_gmt'] = $this->date_gmt;
            }

            // post_modified
            if (!empty($this->modified) and $this->wasChanged('modified')) {
                $this->params['post_modified'] = $this->modified;
            }

            // post_modified_gmt
            if (!empty($this->modified_gmt) and $this->wasChanged('modified_gmt')) {
                $this->params['post_modified_gmt'] = $this->modified_gmt;
            }

            // comment_status
            if (!empty($this->comment_status) and $this->wasChanged('comment_status')) {
                $this->params['comment_status'] = $this->comment_status;
            }

            // ping_status
            if (!empty($this->ping_status) and $this->wasChanged('ping_status')) {
                $this->params['ping_status'] = $this->ping_status;
            }

            // meta_input
            if (!empty($this->meta) and is_array($this->meta) and $this->wasChanged('meta')) {
                $this->params['meta_input'] = $this->meta;
            }

            return $this;
        }

        public function save(): static
        {
            // Check method argument
            $args = func_get_args();

            // Check $fire_after_hooks
            $fire_after_hooks = true;
            if (isset($args[0]) and is_bool($args[0])) {
                $fire_after_hooks = $args[0];
            }

            // setup Params
            $this->setParams();

            // save
            if ($this->id == 0) {
                $this->response = wp_insert_post($this->params, true, $fire_after_hooks);
            } else {
                $this->response = wp_update_post($this->params, true, $fire_after_hooks);
            }

            // boot data
            if (!$this->hasError()) {
                $this->id = $this->response;
                $this->refresh();
            }

            // return static
            return $this;
        }

        public static function new(): static
        {
            return self::instance(0, 'post');
        }

        public static function find($id): static|null
        {
            return self::instance($id, 'post')->get();
        }

        public static function findOr($id, $func): mixed
        {
            $post = self::find($id);
            if (is_null($post) and is_callable($func)) {
                return $func($id);
            }

            return $post;
        }

        public function get(): null|static
        {
            // Get Post
            $post = get_post($this->id);
            if (is_null($post)) {
                return null;
            }

            // setup property
            $this->author = $post->post_author;
            $this->date = $post->post_date;
            $this->date_gmt = $post->post_date_gmt;
            $this->modified = $post->post_modified;
            $this->modified_gmt = $post->post_modified_gmt;
            $this->content = $post->post_content;
            $this->content_filtered = $post->post_content_filtered;
            $this->title = $post->post_title;
            $this->excerpt = $post->post_excerpt;
            $this->status = $post->post_status;
            $this->type = $post->post_type;
            $this->comment_status = $post->comment_status;
            $this->ping_status = $post->ping_status;
            $this->password = $post->post_password;
            $this->slug = $post->post_name;
            $this->parent = $post->post_parent;
            $this->menu_order = $post->menu_order;
            $this->mime_type = $post->post_mime_type;
            $this->guid = $post->guid;
            $this->template = $post->page_template;
            $this->comment_count = $post->comment_count;

            // setup original
            $this->original = $this->toArray();

            // return
            return $this;
        }

        public function delete(): bool
        {
            return (wp_delete_post($this->id, true) instanceof \WP_Post);
        }

        public function trash(): static
        {
            wp_delete_post($this->id, false);
            $this->refresh();
            return $this;
        }

        public function restore(): static
        {
            wp_untrash_post($this->id);
            $this->refresh();
            return $this;
        }

        public function rendered($property)
        {
            if ($property == "title") {
                return get_the_title($this->id);
            }

            if ($property == "content") {
                return apply_filters('the_content', $this->content);
            }

            if ($property == "excerpt") {
                return apply_filters('the_excerpt', $this->excerpt);
            }

            return null;
        }

        public function format(): string
        {
            $format = get_post_format($this->id);
            return (empty($format) ? 'standard' : $format);
        }

        public function permalink($leave_name = false): bool|string
        {
            return get_the_permalink($this->id, $leave_name);
        }

        public function shortlink($context = 'post', $allow_slugs = true): string
        {
            return wp_get_shortlink($this->id, $context, $allow_slugs);
        }

        public function thumbnail(): Attachment|bool
        {
            $thumbnail_id = get_post_thumbnail_id($this->id);
            if (!$thumbnail_id) {
                return false;
            }

            // TODO
            return new Attachment($thumbnail_id);
        }

        public function hasThumbnail(): bool
        {
            return has_post_thumbnail($this->id);
        }

        public function editLink($context = 'display'): ?string
        {
            return get_edit_post_link($this->id, $context);
        }

        public function typeInfo()
        {
            global $wp_post_types;
            return ($wp_post_types[$this->type] ?? null);
        }

        public static function exists($id): bool
        {
            return is_string(get_post_status($id));
        }

        public static function query(array $arg = []): \WP_Query
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
                // 'post_type' => $this->type,
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

        public function list($args = []): array
        {
            $query = self::query($args);
            return ($query->have_posts() ? $query->posts : []);
        }

        public function tags()
        {
            return $this->terms('post_tag');
        }

        public function categories()
        {
            return $this->terms('category');
        }

        public function terms($taxonomy)
        {
            // TODO
            return [];
        }

        public function comments($args = []): array|int
        {
            // TODO
            $comment = new Comment();
            return $comment->list(array_merge(array('post_id' => $this->id, $args)));
        }
    }
}
