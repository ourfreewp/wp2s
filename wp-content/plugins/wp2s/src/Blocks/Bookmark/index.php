<?php
// Path: wp-content/plugins/wp2s/Blocks/Bookmark/index.php

namespace WPS2\Blocks\Bookmark;

$plugin_dir = WP2S_PLUGIN_DIR ?? plugin_dir_path(__FILE__);

$template_slug = $a['template'] ?? '';

$path = trailingslashit($plugin_dir) . 'Blocks/Bookmark/Templates/' . $template_slug . '/index.php';

if (file_exists($path)) {
    include $path;
} else {
    echo '<p>' . esc_html('Template not found for the bookmark') . '</p>';
}