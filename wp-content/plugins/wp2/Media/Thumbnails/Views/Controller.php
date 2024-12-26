<?php
/**
 * Thumbnails Views Controller
 *
 * Renders bylines and captions in post thumbnails.
 *
 * @package WP2\Media\Thumbnails\Views
 */

namespace WP2\Media\Thumbnails\Views;

if (!defined('ABSPATH')) {
    exit;
}

class Controller
{
    public function __construct()
    {
        add_filter('post_thumbnail_html', [$this, 'add_byline_to_thumbnail'], 10, 5);
    }

    /**
     * Append byline and caption to the post thumbnail.
     */
    public function add_byline_to_thumbnail($html, $post_id, $post_thumbnail_id)
    {
        if (!is_single() || !$post_thumbnail_id) {
            return $html;
        }

        $caption = get_the_post_thumbnail_caption($post_id);
        $byline = get_post_meta($post_thumbnail_id, 'byline', true);

        $caption_html = $caption ? "<span class='wp-block-custom-thumbnail-caption'>{$caption}</span>" : '';
        $byline_html = $byline ? "<span class='wp-block-custom-thumbnail-byline'>{$byline}</span>" : '';

        if ($caption || $byline) {
            $html .= "<figcaption class='visually-hidden'>{$caption_html}{$byline_html}</figcaption>";
        }

        return $html;
    }
}