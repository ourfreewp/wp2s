<?php
// Path: wp-content/plugins/wp2/wp2.php
/**
 * Plugin Name: WP2
 * Description: The WP2 plugin.
 * Version: 1.0
 * Author: Vinny S. Green
 **/

namespace WP2;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// define plugin path, url, and version
define('WP2_PATH', plugin_dir_path(__FILE__));
define('WP2_URL', plugin_dir_url(__FILE__));
define('WP2_VERSION', '1.0.0');

add_action('init', function () {

    if (defined("BLOCKSTUDIO")) {
        $directories = [
            'Bio',
            'Blog',
            'Community',
            'Dev',
            'Health',
            'Media',
            'One',
            'Pro',
            'Public',
            'REST',
            'Run',
            'Sh',
            'Singles',
            'Studio',
            'Style',
            'Work',
        ];

        foreach ($directories as $dir) {
            \Blockstudio\Build::init([
                'dir' => WP2_PATH . $dir,
            ]);
        }
    }
});
