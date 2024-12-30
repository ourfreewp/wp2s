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
    protected array $controllers = [
        'Bio\Controller',
        'Blog\Controller',
        'Community\Controller',
        'Contact\Controller',
        'Dev\Controller',
        'Health\Controller',
        'Legal\Controller',
        'Link\Controller',
        'Marketing\Controller',
        'Media\Controller',
        'One\Controller',
        'Pro\Controller',
        'Pub\Controller',
        'REST\Controller',
        'Run\Controller',
        'Sh\Controller',
        'Shop\Controller',
        'Singles\Controller',
        'Studio\Controller',
        'Style\Controller',
        'Wiki\Controller',
        'Work\Controller',
        'Zone\Controller',
    ];

    public function __construct()
    {
        $this->initialize_controllers();
    }

    protected function initialize_controllers(): void
    {
        foreach ($this->controllers as $controller) {
            $class = __NAMESPACE__ . '\\' . $controller;
            if (class_exists($class)) {
                new $class();
            } else {
                error_log("Controller not found: $class");
            }
        }
    }
}

new WP2();