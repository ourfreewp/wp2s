<?php

/**
 * Plugin Name: Extend — Safe SVG
 * Description: A utility plugin for extending the functionality of the Safe SVG plugin.
 * Version: 1.0
 * Author: Vinny S. Green
 **/


// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

define('EXT_SAFE_SVG_NAMESPACE', 'ext_safe_svg');
define('EXT_SAFE_SVG_PREFIX', 'ext_safe_svg_');
define('EXT_SAFE_SVG', 'extend-safe-svg');

define('EXT_SAFE_SVG_PATH', plugin_dir_path(__FILE__));
define('EXT_SAFE_SVG_URL', plugin_dir_url(__FILE__));


add_action('init', function () {

    if (defined("BLOCKSTUDIO")) {
        $directories = [
            'src',
        ];

        foreach ($directories as $dir) {
            Blockstudio\Build::init([
                'dir' => EXT_SAFE_SVG_PATH . $dir,
            ]);
        }
    }
});
