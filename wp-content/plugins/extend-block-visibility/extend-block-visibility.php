<?php

/**
 * Plugin Name: Extend — Block Visibility
 * Description: A utility plugin for extending block visibility plugin.
 * Version: 1.0
 * Author: Vinny S. Green
 **/


// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

define('EXT_BLOCK_VIS_NAMESPACE', 'ext_block_vis');
define('EXT_BLOCK_VIS_PREFIX', 'ext_block_vis_');
define('EXT_BLOCK_VIS', 'extend-block-vis');

define('EXT_BLOCK_VIS_PATH', plugin_dir_path(__FILE__));
define('EXT_BLOCK_VIS_URL', plugin_dir_url(__FILE__));


add_action('init', function () {

    if (defined("BLOCKSTUDIO")) {
        $directories = [
            'src',
        ];

        foreach ($directories as $dir) {
            Blockstudio\Build::init([
                'dir' => EXT_BLOCK_VIS_PATH . $dir,
            ]);
        }
    }
});
