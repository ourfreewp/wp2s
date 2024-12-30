<?php
// Path: wp-content/plugins/wp2/Contact/Controller.php

namespace WP2\Contact;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// define plugin path, url, and version
define('WP2_CONTACT_PATH', plugin_dir_path(__FILE__));
define('WP2_CONTACT_URL', plugin_dir_url(__FILE__));
define('WP2_CONTACT_VERSION', '1.0.0');

class Controller
{
    public function __construct()
    {}
}
