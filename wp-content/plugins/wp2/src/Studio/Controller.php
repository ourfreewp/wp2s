<?php
// Path: wp-content/plugins/wp2/Studio/Controller.php

namespace WP2\Studio;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// define plugin path, url, and version
define('WP2_STUDIO_PATH', plugin_dir_path(__FILE__));
define('WP2_STUDIO_URL', plugin_dir_url(__FILE__));
define('WP2_STUDIO_VERSION', '1.0.0');

class Controller
{
    public function __construct()
    {
        new Themes\Controller();
        new Settings\Controller();
        
    }
}
