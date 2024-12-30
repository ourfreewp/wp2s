<?php
// Path: wp-content/plugins/wp2/wp2.php
/**
 * Plugin Name: WP2
 * Description: The WP2 plugin.
 * Version: 1.0
 * Author: Vinny S. Green
 **/

namespace WP2;

defined('ABSPATH') or exit;

define('WP2_PATH', plugin_dir_path(__FILE__));
define('WP2_URL', plugin_dir_url(__FILE__));
define('WP2_VERSION', '1.0.0');
define('WP2_NAMESPACE', 'wp2');

spl_autoload_register(function ($class) {
    $base_namespace = 'WP2\\';

    if (strpos($class, $base_namespace) === 0) {
        $relative_class = substr($class, strlen($base_namespace));
        $file = WP2_PATH . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
});
// Initialize Core Controllers
new Studio\Controller();
new Singles\Controller();
new Zone\Controller();
