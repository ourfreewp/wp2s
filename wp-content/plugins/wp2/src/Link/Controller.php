<?php
// Path: wp-content/plugins/wp2/Link/Controller.php

namespace WP2\Link;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// define plugin path, url, and version
define('WP2_LINK_PATH', plugin_dir_path(__FILE__));
define('WP2_LINK_URL', plugin_dir_url(__FILE__));
define('WP2_LINK_VERSION', '1.0.0');

class Controller
{
    public function __construct()
    {}
}
