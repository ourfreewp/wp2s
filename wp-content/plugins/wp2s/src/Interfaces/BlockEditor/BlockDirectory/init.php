<?php
// Path: wp-content/plugins/wp2s/src/Interfaces/BlockEditor/BlockDirectory/init.php
namespace WP2\Interfaces\BlockEditor\BlockDirectory;

class Controller
{
    public function __construct()
    {
        remove_action('enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets');
    }
}

new Controller();