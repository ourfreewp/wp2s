<?php
// Path: wp-content/plugins/wp2/Studio/init.php

namespace WP2\Studio;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// define plugin path, url, and version
define('WP2_STUDIO_PATH', plugin_dir_path(__FILE__));
define('WP2_STUDIO_URL', plugin_dir_url(__FILE__));
define('WP2_STUDIO_VERSION', '1.0.0');

// Autoloader function to load classes from the `src` directory.
spl_autoload_register(function ($class) {
    // Base namespace for this plugin.
    $base_namespace = 'WP2\\Studio';

    // Ensure the class is within our namespace.
    if (strpos($class, $base_namespace) === 0) {
        // Remove the base namespace from the class.
        $relative_class = str_replace($base_namespace . '\\', '', $class);

        // Replace namespace separators with directory separators.
        $file = plugin_dir_path(__FILE__) . str_replace('\\', '/', $relative_class) . '.php';

        // Check if the file exists and require it.
        if (file_exists($file)) {
            require $file;
        }
    }
});


new Themes\Controller();
new Settings\Controller();
