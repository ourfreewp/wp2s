<?php

namespace WP2\Community;

defined('ABSPATH') || exit;

// Define constants
if (!defined('WP2_COMMUNITY_TEMPLATE_VERSION')) {
    define('WP2_COMMUNITY_TEMPLATE_VERSION', '1.0');
}
if (!defined('WP2_COMMUNITY_TEMPLATE_PATH')) {
    define('WP2_COMMUNITY_TEMPLATE_PATH', plugin_dir_path(__FILE__));
}
if (!defined('WP2_COMMUNITY_TEMPLATE_URL')) {
    define('WP2_COMMUNITY_TEMPLATE_URL', plugin_dir_url(__FILE__));
}
if (!defined('WP2_COMMUNITY_TEMPLATE_SLUG')) {
    define('WP2_COMMUNITY_TEMPLATE_SLUG', basename(dirname(__FILE__)));
}

// Register the autoloader
spl_autoload_register(function ($class) {
    $namespace = 'WP2\\Community\\';
    if (strpos($class, $namespace) === 0) {
        $relative_class = substr($class, strlen($namespace));
        $relative_path = str_replace('\\', DIRECTORY_SEPARATOR, $relative_class);
        $relative_path = strtolower($relative_path);
        $file = WP2_COMMUNITY_TEMPLATE_PATH . $relative_path . '.php';

        if (file_exists($file)) {
            require_once $file;
        } elseif (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("[WP2 Express Template] Autoloader Error: Unable to load class '{$class}'. Expected path: {$file}");
        }
    }
});

// Initialize the plugin
add_action('plugins_loaded', function () {
    $communityUrl = WP2_COMMUNITY_URL;
    $graphqlUrl = $communityUrl . '/graphql';

    $jwtConfig = [
        'private_key' => WP2_COMMUNITY_JWT_PRIVATE_KEY,
        'algorithm' => WP2_COMMUNITY_JWT_ALGORITHM,
    ];

    // Initialize the extension manager
    Extensions\ExtensionManager::initialize($graphqlUrl, $jwtConfig);
});
