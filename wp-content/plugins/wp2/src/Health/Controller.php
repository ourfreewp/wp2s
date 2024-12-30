<?php
// Path: wp-content/plugins/wp2/Health/Controller.php

namespace WP2\Health;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// define plugin path, url, and version
define('WP2_HEALTH_PATH', plugin_dir_path(__FILE__));
define('WP2_HEALTH_URL', plugin_dir_url(__FILE__));
define('WP2_HEALTH_VERSION', '1.0.0');

class Controller
{
    public function __construct()
    {}
}
