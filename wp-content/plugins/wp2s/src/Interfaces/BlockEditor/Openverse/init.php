<?php
// Path: wp-content/plugins/wp2s/src/Interfaces/BlockEditor/Openverse/init.php
namespace WP2\Interfaces\BlockEditor\Openverse;

class Controller
{
    public function __construct()
    {
        add_filter('block_editor_settings_all', [$this, 'disable_openverse_media_category']);
    }

    /**
     * Disable Openverse media category in the block editor.
     *
     * @param array $settings The editor settings.
     * @return array Modified settings with Openverse disabled.
     */
    public function disable_openverse_media_category($settings)
    {
        $settings['enableOpenverseMediaCategory'] = false;
        return $settings;
    }

}

new Controller();