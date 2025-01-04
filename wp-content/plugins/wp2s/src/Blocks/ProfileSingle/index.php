<?php
// Path: wp-content/plugins/wp2s/Blocks/ProfileSingle/ProfileSingle.php
namespace WPS2\Blocks\ProfileSingle;

// Get post type from block or fallback to empty string
$post_type = $block['postType'] ?? '';

$prefix = WP2S_PREFIX ?? 'wp2s_';
$plugin_dir = WP2S_PLUGIN_DIR ?? plugin_dir_path(__FILE__);

// Trim prefix from postType (if exists)
$template_slug = str_replace($prefix, '', $post_type);

// Sanitize and construct the template path
$path = trailingslashit($plugin_dir) . 'Blocks/ProfileSingle/Templates/' . $template_slug . '/index.php';

if (file_exists($path)) {
    include $path;
} else {
    echo '<p>' . esc_html('Template not found for post type: ' . $post_type) . '</p>';
}