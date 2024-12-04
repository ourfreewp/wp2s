<?php
// Path: wp-content/plugins/wp2-rest/wp2-rest.php
/**
 * Plugin Name: WP2 REST
 * Description: This plugin provides a REST API for the WP2.
 */

namespace WP2\REST;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// Define plugin path, URL, and version
define('WP2_REST_PATH', plugin_dir_path(__FILE__));
define('WP2_REST_URL', plugin_dir_url(__FILE__));
define('WP2_REST_VERSION', '1.0.0');
define('WP2_REST_NAMESPACE', 'wp2');

// Autoloader function to load classes from the `src/REST` directory.
spl_autoload_register(function ($class) {
    // Base namespace for this plugin's REST functionality.
    $base_namespace = 'WP2\\REST';

    // Ensure the class is within the 'WP2\REST' namespace.
    if (strpos($class, $base_namespace . '\\') === 0) {
        // Remove the base namespace from the class.
        $relative_class = substr($class, strlen($base_namespace . '\\'));

        // Replace namespace separators with directory separators.
        $file = plugin_dir_path(__FILE__) . 'src/REST/' . str_replace('\\', '/', $relative_class) . '.php';

        // Check if the file exists and require it.
        if (file_exists($file)) {
            require $file;
            error_log("Autoloader loaded class: $class from file: $file");
        } else {
            error_log("Autoloader failed to find file: $file");
        }
    }
});

try {
    $network_controller = 'WP2\\REST\\Network\\Sites\\Controller';

    if (!class_exists($network_controller)) {
        throw new \Exception("The class $network_controller does not exist");
    } else {
        new Network\Sites\Controller();
    }
} catch (\Exception $e) {
    error_log('WP2\REST: ' . $e->getMessage());
}
