<?php
// Path: wp-content/plugins/wp2/Singles/Controller.php

namespace WP2\Singles;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// define plugin path, url, and version
define('WP2_SINGLES_PATH', plugin_dir_path(__FILE__));
define('WP2_SINGLES_URL', plugin_dir_url(__FILE__));
define('WP2_SINGLES_VERSION', '1.0.0');

class Controller
{
    public function __construct()
    {}
}
