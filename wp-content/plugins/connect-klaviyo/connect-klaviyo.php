<?php
// Path: wp-content/plugins/connect-klaviyo/connect-klaviyo.php
/**
 * Plugin Name: Connect — Klaviyo
 * Description: A utility plugin for connecting Klaviyo to WordPress.
 * Version: 1.0
 * Author: Vinny S. Green
 **/


// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

define('CONNECT_KLAVIYO_NAMESPACE', 'connect_klaviyo');
define('CONNECT_KLAVIYO_PREFIX', 'connect_klaviyo_');
define('CONNECT_KLAVIYO', 'connect-klaviyo');

define('CONNECT_KLAVIYO_PATH', plugin_dir_path(__FILE__));
define('CONNECT_KLAVIYO_URL', plugin_dir_url(__FILE__));


add_action('init', function () {

    if (defined("BLOCKSTUDIO")) {
        $directories = [
            'src/Scripts',
            'src/Blocks',
        ];

        foreach ($directories as $dir) {
            Blockstudio\Build::init([
                'dir' => CONNECT_KLAVIYO_PATH . $dir,
            ]);
        }
    }
});
