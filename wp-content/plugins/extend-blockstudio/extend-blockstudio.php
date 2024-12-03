<?php
// Path: wp-content/plugins/extend-blockstudio/extend-blockstudio.php
/**
 * Plugin Name: Blockstudio — Extended
 * Description: This plugin extends the Blockstudio plugin.
 */

namespace WP2\Extend\Blockstudio;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// define plugin path, url, and version
define('WP2S_EXT_BLOCKSTUDIO_PATH', plugin_dir_path(__FILE__));
define('WP2S_EXT_BLOCKSTUDIO_URL', plugin_dir_url(__FILE__));
define('WP2S_EXT_BLOCKSTUDIO_VERSION', '1.0.0');

// Autoloader function to load classes from the `src` directory.
spl_autoload_register(function ($class) {
    // Base namespace for this plugin.
    $base_namespace = 'WP2\\Extend\\Blockstudio';

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


new Themes\Controller();
new Settings\Controller();
