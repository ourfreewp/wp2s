<?php
// Path: wp-content/plugins/wp2/One/Controller.php

namespace WP2\One;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// define plugin path, url, and version
define('WP2_ONE_PATH', plugin_dir_path(__FILE__));
define('WP2_ONE_URL', plugin_dir_url(__FILE__));
define('WP2_ONE_VERSION', '1.0.0');

class Controller
{
    public function __construct()
    {}
}
