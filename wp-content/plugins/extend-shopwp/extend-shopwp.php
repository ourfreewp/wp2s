<?php
/**
 * Plugin Name: Extend — ShopWP
 * Description: A utility plugin for extending ShopWP plugin.
 * Version: 1.0
 * Author: Vinny S. Green
 **/


// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

define('EXT_SHOPWP_NAMESPACE', 'ext_shopwp');
define('EXT_SHOPWP_PREFIX', 'ext_shopwp_');
define('EXT_SHOPWP', 'extend-shopwp');

define('EXT_SHOPWP_PATH', plugin_dir_path(__FILE__));
define('EXT_SHOPWP_URL', plugin_dir_url(__FILE__));


add_action('init', function () {

    if (defined("BLOCKSTUDIO")) {
        $directories = [
            'src',
        ];

        foreach ($directories as $dir) {
            Blockstudio\Build::init([
                'dir' => EXT_SHOPWP_PATH . $dir,
            ]);
        }
    }
});