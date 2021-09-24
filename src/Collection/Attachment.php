<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('Attachment')) {

    trait Attachment
    {

        public static $ImageExtensions = array('jpg', 'jpeg', 'jpe', 'gif', 'png', 'webp', 'svg', 'bmp');

        public function get_upload_dir()
        {
            # https://developer.wordpress.org/reference/functions/wp_upload_dir/
            return wp_get_upload_dir();
        }

        public function get_attachment_url($attachment_id)
        {
            return wp_get_attachment_url($attachment_id);
        }

        public function get_attachment_image_src($attachment_id, $size = 'thumbnail', $return = 'all')
        {
            $attachment = wp_get_attachment_image_src($attachment_id, $size);
            if (!$attachment) {
                return false;
            }

            return ($return == "src" ? $attachment[0] : $attachment);
        }

        public function get_attachment_file_path($attachment_id, $unfiltered = true)
        {
            return get_attached_file($attachment_id, $unfiltered);
        }

        public function get_attachment_metadata($attachment_id, $unfiltered = true)
        {
            return wp_get_attachment_metadata($attachment_id, $unfiltered);
        }

        public function get_prepare_attachment_for_js($attachment_id)
        {
            return wp_prepare_attachment_for_js($attachment_id);
        }

        public function wp_delete_attachment($attachment_id, $force_delete = true)
        {
            return wp_delete_attachment($attachment_id, $force_delete);
        }

        public function get_attachment_cover_id($attachment_id)
        {
            # Usage for video Or Audio
            # Thumbnail Attachment has a post meta with name of "_cover_hash"
            return get_post_meta($attachment_id, '_thumbnail_id', true);
        }

        public function get_attachment_file_size($attachment_id)
        {
            return filesize($this->get_attachment_file_path($attachment_id));
        }

        public function get_size_format($bytes, $decimals = 0)
        {
            return size_format($bytes, $decimals);
        }

        public function get_attachment_post_mime_type($attachment_id)
        {
            return get_post_mime_type($attachment_id);
        }

        public function get_attachment_is($attachment_id, $file_path = false)
        {
            $path = ($file_path === false ? $this->get_attachment_file_path($attachment_id) : $file_path);
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