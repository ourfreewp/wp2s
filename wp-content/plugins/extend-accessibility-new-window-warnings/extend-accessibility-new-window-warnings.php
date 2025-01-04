<?php
// Path: wp-content/plugins/extend-accessibility-new-window-warnings/extend-accessibility-new-window-warnings.php
/**
 * Plugin Name: Extend — Accessibility New Window Warnings
 * Description: A utility plugin for extending accessibility new window warnings plugin.
 * Version: 1.0
 * Author: Vinny S. Green
 **/


// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

define('EXT_ANWW_NAMESPACE', 'ext_anww');
define('EXT_ANWW_PREFIX', 'ext_anww_');
define('EXT_ANWW', 'extend-accessibility-new-window-warnings');

define('EXT_ANWW_PATH', plugin_dir_path(__FILE__));
define('EXT_ANWW_URL', plugin_dir_url(__FILE__));


add_action('init', function () {

    if (defined("BLOCKSTUDIO")) {
        $directories = [
            'src',
        ];

        foreach ($directories as $dir) {
            Blockstudio\Build::init([
                'dir' => EXT_ANWW_PATH . $dir,
            ]);
        }
    }
});
