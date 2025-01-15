<?php
// Path: wp-content/plugins/connect-help-scout/connect-help-scout.php
/**
 * Plugin Name: Connect — Help Scout
 * Description: A utility plugin for connecting Help Scout to WordPress.
 * Version: 1.0
 * Author: Vinny S. Green
 **/


// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

define('CONNECT_HELP_SCOUT_NAMESPACE', 'connect_help_scout');
define('CONNECT_HELP_SCOUT_PREFIX', 'connect_help_scout_');
define('CONNECT_HELP_SCOUT', 'connect-help-scout');

define('CONNECT_HELP_SCOUT_PATH', plugin_dir_path(__FILE__));
define('CONNECT_HELP_SCOUT_URL', plugin_dir_url(__FILE__));


add_action('init', function () {

    if (defined("BLOCKSTUDIO")) {
        $directories = [
            'src/Scripts',
            'src/Blocks',
        ];

        foreach ($directories as $dir) {
            Blockstudio\Build::init([
                'dir' => CONNECT_HELP_SCOUT_PATH . $dir,
            ]);
        }
    }
});
