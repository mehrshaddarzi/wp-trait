<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Attachment')) {

    class Attachment
    {
        /**
         * Attachment ID
         *
         * @var int
         */
        public $attachment_id;

        /**
         * Meta Class
         */
        public $meta;

        /**
         * Image Extensions
         *
         * @var string[]
         */
        public static $ImageExtensions = array('jpg', 'jpeg', 'jpe', 'gif', 'png', 'webp', 'svg', 'bmp');

        public function __construct($attachment_id = null)
        {
            $this->attachment_id = $attachment_id;
            $this->meta = new Meta('attachment', $this->attachment_id);
        }

        public function upload_dir()
        {
            # https://developer.wordpress.org/reference/functions/wp_upload_dir/
            return (object)wp_get_upload_dir();
        }

        public function url($attachment_id = null)
        {
            return wp_get_attachment_url((is_null($attachment_id) ? $this->attachment_id : $attachment_id));
        }

        public function src($size = 'thumbnail', $return = 'all', $attachment_id = null)
        {
            $attachment = wp_get_attachment_image_src((is_null($attachment_id) ? $this->attachment_id : $attachment_id), $size);
            if (!$attachment) {
                return false;
            }

            return ($return == "src" ? $attachment[0] : (object)$attachment);
        }

        public function path($attachment_id = null, $unfiltered = true)
        {
            return get_attached_file((is_null($attachment_id) ? $this->attachment_id : $attachment_id), $unfiltered);
        }

        public function metadata($attachment_id = null, $unfiltered = true)
        {
            return wp_get_attachment_metadata((is_null($attachment_id) ? $this->attachment_id : $attachment_id), $unfiltered);
        }

        public function delete($force_delete = true, $attachment_id = null)
        {
            return wp_delete_attachment((is_null($attachment_id) ? $this->attachment_id : $attachment_id), $force_delete);
        }

        public function cover($attachment_id = null)
        {
            # Usage for video Or Audio
            # Thumbnail Attachment has a post meta with name of "_cover_hash"
            return get_post_meta((is_null($attachment_id) ? $this->attachment_id : $attachment_id), '_thumbnail_id', true);
        }

        public function size($attachment_id = null)
        {
            return filesize($this->path((is_null($attachment_id) ? $this->attachment_id : $attachment_id)));
        }

        public function sizeFormat($bytes, $decimals = 0)
        {
            return size_format($bytes, $decimals);
        }

        public function mime_type($attachment_id = null)
        {
            return get_post_mime_type((is_null($attachment_id) ? $this->attachment_id : $attachment_id));
        }

        public function is($attachment_id = null, $file_path = false)
        {
            $path = ($file_path === false ? $this->path((is_null($attachment_id) ? $this->attachment_id : $attachment_id)) : $file_path);
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            # @see https://developer.wordpress.org/reference/functions/wp_attachment_is/
            $image_ext = self::$ImageExtensions;
            $audio_ext = wp_get_audio_extensions();
            $video_ext = wp_get_video_extensions();

            if (in_array($ext, $image_ext)) {
                return 'image';
            } elseif (in_array($ext, $video_ext)) {
                return 'video';
            } elseif (in_array($ext, $audio_ext)) {
                return 'audio';
            }

            return 'other';
        }

        public function upload($file_id, $post_id = 0, $post_data = array(), $overrides = array('test_form' => false))
        {
            # These files need to be included as dependencies when on the front end.
            $this->requirePHPImage();

            # (attachment_id || WP_Error)
            return media_handle_upload($file_id, $post_id, $post_data, $overrides);
        }

        public function generate_thumbnail($attachment_id)
        {
            $this->requirePHPImage();
            $attachment_id = (is_null($attachment_id) ? $this->attachment_id : $attachment_id);
            $file_path = $this->path($attachment_id);
            $attach_data = wp_generate_attachment_metadata($attachment_id, $file_path);
            wp_update_attachment_metadata($attachment_id, $attach_data);
        }

        public function requirePHPImage()
        {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
        }

        public function get_wordpress_image_sizes($size = '')
        {
            # additional by theme or plugin
            $wp_additional_image_sizes = wp_get_additional_image_sizes();

            # WordPress Core
            $get_intermediate_image_sizes = get_intermediate_image_sizes();

            # Create the full array with sizes and crop info
            $sizes = array();
            foreach ($get_intermediate_image_sizes as $_size) {
                if (in_array($_size, array('thumbnail', 'medium', 'large'))) {
                    $sizes[$_size]['width'] = get_option($_size . '_size_w');
                    $sizes[$_size]['height'] = get_option($_size . '_size_h');
                    $sizes[$_size]['crop'] = (bool)get_option($_size . '_crop');
                } elseif (isset($wp_additional_image_sizes[$_size])) {
                    $sizes[$_size] = array(
                        'width' => $wp_additional_image_sizes[$_size]['width'],
                        'height' => $wp_additional_image_sizes[$_size]['height'],
                        'crop' => $wp_additional_image_sizes[$_size]['crop']
                    );
                }
            }

            // Get only 1 size if found
            if ($size) {
                if (isset($sizes[$size])) {
                    return $sizes[$size];
                } else {
                    return false;
                }
            }
            return $sizes;
        }

    }

}