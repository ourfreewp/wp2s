<?php

/**
 * Plugin Name: WP2S
 * Description: The core plugin for WP2S.
 * Version: 1.0
 * Author: Vinny S. Green
 **/


// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

define('WP2S_NAMESPACE', 'wp2s');

define('WP2S_PREFIX', 'wp2s_');
define('WP2S_TEXTDOMAIN', 'wp2s');

define('WP2S_MU_PLUGIN_NAME', 'wp2s');
define('WP2S_MU_PLUGIN_DIR', __DIR__ . '/' . WP2S_MU_PLUGIN_NAME . '/');

// Get the site domain
$site_domain = defined('WP_SITEURL') ? parse_url(WP_SITEURL, PHP_URL_HOST) : parse_url(site_url(), PHP_URL_HOST);
$site_domain = sanitize_title($site_domain);

// Define the standard plugin name
define('WP2S_STD_PLUGIN_NAME', WP2S_MU_PLUGIN_NAME . '-' . $site_domain);
define('WP2S_STD_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins/' . WP2S_STD_PLUGIN_NAME . '/');

add_action('init', function () {

    if (defined("BLOCKSTUDIO")) {
        $directories = [
            'Assets',
            'Integrations',
            'Types',
        ];

        foreach ($directories as $dir) {
            Blockstudio\Build::init([
                'dir' => plugin_dir_path(__FILE__) . $dir,
            ]);
        }
    }
});
