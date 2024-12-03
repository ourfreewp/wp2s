<?php
// Path: wp-content/plugins/extend-blockstudio/src/Themes/Controller.php
namespace WP2\Extend\Blockstudio\Themes;

class Controller
{
    public function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    public function init()
    {
        // Ensure the BLOCKSTUDIO constant is defined.
        if (defined('BLOCKSTUDIO')) {
            // Check if the Blockstudio\Build class exists.
            if (!class_exists('Blockstudio\Build')) {
                error_log('Blockstudio\Build class does not exist. Ensure the Blockstudio plugin is active and properly loaded.');
                return;
            }

            // Get all themes with the blockstudio tag.
            $themes = $this->get_blockstudio_themes();

            foreach ($themes as $theme) {
                $theme_dir = $theme->get_stylesheet_directory();

                // Initialize Blockstudio\Build with the theme directory.
                \Blockstudio\Build::init([
                    'dir' => $theme_dir,
                ]);
            }
        } else {
            error_log('BLOCKSTUDIO constant is not defined. Ensure the Blockstudio plugin is properly initialized.');
        }
    }

    private function get_blockstudio_themes(): array
    {
        // Get all installed themes.
        $all_themes = wp_get_themes();

        // Filter themes with the 'blockstudio' tag.
        return array_filter($all_themes, function ($theme) {
            $tags = $theme->get('Tags');
            return is_array($tags) && in_array('blockstudio', $tags, true);
        });
    }
}
