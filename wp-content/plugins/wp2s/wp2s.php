<?php
// Path: wp-content/plugins/wp2s/wp2s.php
/**
 * Plugin Name: WP2S
 * Description: The core plugin for WP2S.
 * Version: 1.0
 * Author: Vinny S. Green
 **/

 namespace WP2S;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define core plugin constants
define('WP2S_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP2S_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * WP2S Bootstrap Class
 */
class Bootstrap {

    public function __construct() {
        $this->define_constants();
        add_action('init', [$this, 'initialize_blockstudio']);
    }

    private function define_constants() {

        if (!defined('WP2S_NAMESPACE')) {
            define('WP2S_NAMESPACE', 'wp2s');
            define('WP2S_PREFIX', 'wp2s_');
            define('WP2S_TEXTDOMAIN', 'wp2s');

            define('WP2S_MU_PLUGIN_NAME', 'wp2s');
            define('WP2S_MU_PLUGIN_DIR', WP2S_PLUGIN_DIR . WP2S_MU_PLUGIN_NAME . '/');

            // Generate site-specific plugin directory
            $site_domain = defined('WP_SITEURL') 
                ? parse_url(WP_SITEURL, PHP_URL_HOST) 
                : parse_url(site_url(), PHP_URL_HOST);
            $site_domain = sanitize_title($site_domain);

            define('WP2S_STD_PLUGIN_NAME', WP2S_MU_PLUGIN_NAME . '-' . $site_domain);
            define('WP2S_STD_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins/' . WP2S_STD_PLUGIN_NAME . '/');
        }
    }

    public function initialize_blockstudio() {
        if (defined('BLOCKSTUDIO')) {
            $directories = $this->get_plugin_directories();
            $this->initialize_directories($directories);
        }
    }

    private function get_plugin_directories() {
        return [
            'Assets',
            'Blocks',
            'Pixels',
            'Programs/Membership',
            'Interfaces/AdminBar',
            'Modules',
            'Integrations/Iubenda',
            'Shortcodes',
            'Singles',
            'Types',
            'Users',
            'Work/Projects',
            'Work/Issues',
            'Work/Roadmaps',
        ];
    }

    private function initialize_directories($directories) {
        foreach ($directories as $dir) {
            $path = WP2S_PLUGIN_DIR . 'src/' . $dir;

            if (is_dir($path)) {
                \Blockstudio\Build::init([
                    'dir' => $path,
                ]);
            }
        }
    }
}

// Initialize the Bootstrap class
new Bootstrap();