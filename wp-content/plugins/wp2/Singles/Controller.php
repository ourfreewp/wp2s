<?php
// Path: wp-content/plugins/wp2/Singles/Controller.php
/**
 * Module Name: WP2 Singles
 * Description: Registers singular entities such as post types and taxonomies.
 */

namespace WP2\Singles;

defined('ABSPATH') or exit;

class Controller
{
    public function __construct()
    {
        // Initialize Block submodule
        new Block\Controller();
    }
}
