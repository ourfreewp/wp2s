<?php
// Path: wp-content/plugins/extend-ws-form/extend-ws-form.php
/**
 * Plugin Name: Extend — WS Form
 * Description: A utility plugin for extending WS Form plugin.
 * Version: 1.0
 * Author: Vinny S. Green
 **/


// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

define('EXT_WSFORM_NAMESPACE', 'ext_wsform');
define('EXT_WSFORM_PREFIX', 'ext_wsform_');
define('EXT_WSFORM', 'extend-ws-form');

define('EXT_WSFORM_PATH', plugin_dir_path(__FILE__));
define('EXT_WSFORM_URL', plugin_dir_url(__FILE__));


add_action('init', function () {

    if (defined("BLOCKSTUDIO")) {
        $directories = [
            'src/Blocks',
            'src/BlockPatterns',
        ];

        foreach ($directories as $dir) {
            Blockstudio\Build::init([
                'dir' => EXT_WSFORM_PATH . $dir,
            ]);
        }
    }
});
