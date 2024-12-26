<?php
/**
 * Thumbnails Settings Controller
 *
 * Registers meta boxes and sync logic for thumbnail bylines.
 *
 * @package WP2\Media\Thumbnails\Settings
 */

namespace WP2\Media\Thumbnails\Settings;

if (!defined('ABSPATH')) {
    exit;
}

class Controller
{
    private $prefix = 'thumbnail_image_';

    public function __construct()
    {
        add_filter('rwmb_meta_boxes', [$this, 'register_meta_boxes']);
        add_action('save_post', [$this, 'sync_featured_image_byline']);
        add_action('admin_init', [$this, 'sync_thumbnail_byline_on_edit']);
        add_action('after_setup_theme', [$this, 'register_custom_image_sizes']);
    }

    /**
     * Register Meta Boxes for attachments and posts with thumbnails.
     */
    public function register_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title'      => 'Additional Settings',
            'id'         => 'attachment_settings',
            'post_types' => ['attachment'],
            'media_modal' => true,
            'fields'     => [
                [
                    'id'          => 'byline',
                    'type'        => 'textarea',
                    'placeholder' => __('FirstName LastName / SourceName', 'wp2'),
                ],
            ],
        ];

        $meta_boxes[] = [
            'title'      => 'Thumbnail Settings',
            'id'         => 'thumbnail_settings',
            'post_types' => $this->get_thumbnail_supported_post_types(),
            'context'    => 'side',
            'priority'   => 'high',
            'autosave'   => true,
            'fields'     => [
                [
                    'id'          => $this->prefix . 'byline',
                    'name'        => 'Byline',
                    'type'        => 'textarea',
                    'desc'        => 'Overwrite the featured image byline upon save.',
                    'placeholder' => 'FirstName LastName / SourceName',
                ],
            ],
        ];

        return $meta_boxes;
    }

    /**
     * Sync the byline from the post to the featured image.
     */
    public function sync_featured_image_byline($post_id)
    {
        $byline = get_post_meta($post_id, $this->prefix . 'byline', true);

        if (empty($byline) || !has_post_thumbnail($post_id)) {
            return;
        }

        $thumbnail_id = get_post_thumbnail_id($post_id);
        $current_byline = get_post_meta($thumbnail_id, 'byline', true);

        if ($current_byline !== $byline) {
            update_post_meta($thumbnail_id, 'byline', $byline);
        }
    }

    /**
     * Sync the byline from the featured image to the post during editing.
     */
    public function sync_thumbnail_byline_on_edit()
    {
        if (!is_admin() || ($_GET['action'] ?? '') !== 'edit') {
            return;
        }

        $post_id = $_GET['post'] ?? null;

        if (!$post_id || !has_post_thumbnail($post_id)) {
            return;
        }

        $thumbnail_id = get_post_thumbnail_id($post_id);
        $thumbnail_byline = get_post_meta($thumbnail_id, 'byline', true);
        $current_post_byline = get_post_meta($post_id, $this->prefix . 'byline', true);

        if (!empty($thumbnail_byline) && empty($current_post_byline)) {
            update_post_meta($post_id, $this->prefix . 'byline', $thumbnail_byline);
        }
    }

    /**
     * Register additional custom image sizes.
     */
    public function register_custom_image_sizes()
    {
        $image_sizes = [
            'background' => [
                'width'  => 1920,
                'height' => 1080,
                'crop'   => true,
            ],
            'story' => [
                'width'  => 1080,
                'height' => 1920,
                'crop'   => true,
            ],
            'post' => [
                'width'  => 1200,
                'height' => 630,
                'crop'   => true,
            ],
        ];

        foreach ($image_sizes as $name => $size) {
            add_image_size($name, $size['width'], $size['height'], $size['crop']);
        }
    }


    /**
     * Get post types that support thumbnails.
     */
    private function get_thumbnail_supported_post_types()
    {
        return array_filter(get_post_types(), function ($post_type) {
            return post_type_supports($post_type, 'thumbnail');
        });
    }
}