<?php
// Path: wp-content/plugins/wp2/Wiki/Controller.php

namespace WP2\Wiki;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// define plugin path, url, and version
define('WP2_WIKI_PATH', plugin_dir_path(__FILE__));
define('WP2_WIKI_URL', plugin_dir_url(__FILE__));
define('WP2_WIKI_VERSION', '1.0.0');

class Controller
{
    public function __construct()
    {}
}
