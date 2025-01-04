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

// Autoloader
require_once WP2_PATH . 'vendor/autoload.php';

class WP2
{
    public function __construct()
    {
        new Singles\Controller();
        new Studio\Controller();
    }
}

new WP2();