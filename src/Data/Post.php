<?php

namespace WPTrait\Data;

use WPTrait\Abstracts\Data;

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
         * Page template to use.
         *
         * @var string
         */
        public string $template = '';

        public function __construct($id = 0)
        {
            parent::__construct($id, 'post');
            if ($this->id > 0) {
                $this->get();
            }
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
                'status' => $this->status,
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

            // reset changed
            $this->changed = [];

            // return static
            return $this;
        }

        public static function new(): static
        {
            return self::instance(0, 'post');
        }

        public static function find($id)
        {
            return self::instance($id, 'post');
        }

        public function get()
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

            // setup original
            $this->original = $this->toArray();
        }

        public function toArray(): array
        {
            return get_object_vars($this);
        }

        public function delete(): bool|array|\WP_Post|null
        {
            return wp_delete_post($this->id, true);
        }

        public function trash(): bool|array|\WP_Post|null
        {
            return wp_delete_post($this->id, false);
        }

        public function restore($status = 'draft'): static
        {
            $this->status = $status;
            return $this->save();
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

        public static function query()
        {
            // TODO
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
