<?php
// Path: wp-content/plugins/connect-bettermode/connect-bettermode.php
/**
 * Plugin Name: Connect — Bettermode
 * Description: A utility plugin for connecting Bettermode to WordPress.
 * Version: 1.0
 * Author: Vinny S. Green
 **/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

define('CONNECT_BETTERMODE_NAMESPACE', 'connect_bettermode');
define('CONNECT_BETTERMODE_PREFIX', 'connect_bettermode_');
define('CONNECT_BETTERMODE', 'connect-bettermode');

define('CONNECT_BETTERMODE_PATH', plugin_dir_path(__FILE__));
define('CONNECT_BETTERMODE_URL', plugin_dir_url(__FILE__));


add_action('init', function () {

    if (defined("BLOCKSTUDIO")) {
        $directories = [
            'src/Scripts',
            'src/Blocks',
        ];

        foreach ($directories as $dir) {
            Blockstudio\Build::init([
                'dir' => CONNECT_BETTERMODE_PATH . $dir,
            ]);
        }
    }
});
