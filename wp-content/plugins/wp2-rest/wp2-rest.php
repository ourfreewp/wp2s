<?php
// Path: wp-content/plugins/wp2-rest/wp2-rest.php
/**
 * Plugin Name: WP2 REST
 * Description: This plugin provides a REST API for the WP2.
 */

namespace WP2\REST;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// define plugin path, url, and version
define('WP2_REST_PATH', plugin_dir_path(__FILE__));
define('WP2_REST_URL', plugin_dir_url(__FILE__));
define('WP2_REST_VERSION', '1.0.0');
define('WP2_REST_NAMESPACE', 'wp2');

// Autoloader function to load classes from the `src` directory.
spl_autoload_register(function ($class) {
    // Base namespace for this plugin.
    $base_namespace = 'WP2\\REST';

    // Ensure the class is within our namespace.
    if (strpos($class, $base_namespace) === 0) {
        // Remove the base namespace from the class.
        $relative_class = str_replace($base_namespace . '\\', '', $class);

        // Replace namespace separators with directory separators.
        $file = plugin_dir_path(__FILE__) . 'src/' . str_replace('\\', '/', $relative_class) . '.php';

        // Check if the file exists and require it.
        if (file_exists($file)) {
            require $file;
        }
    }
});
