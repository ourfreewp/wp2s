<?php
// Path: wp-content/plugins/wp2/Pub/Controller.php

namespace WP2\Pub;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// define plugin path, url, and version
define('WP2_PUB_PATH', plugin_dir_path(__FILE__));
define('WP2_PUB_URL', plugin_dir_url(__FILE__));
define('WP2_PUB_VERSION', '1.0.0');

class Controller
{
    public function __construct()
    {}
}
