<?php

namespace WP2\Studio\Helpers\Loader;

defined('ABSPATH') || exit; // Prevent direct access

class Controller
{
    /**
     * Paths to scan for studio.json files.
     *
     * @var array
     */
    private array $scan_paths = [
        WP_CONTENT_DIR . '/themes',
        WP_CONTENT_DIR . '/mu-plugins',
    ];

    /**
     * Prefix for plugin files to initialize.
     *
     * @var string
     */
    private string $plugin_prefix;

    /**
     * Constructor to initialize scanning and loading processes.
     */
    public function __construct()
    {
        $this->set_plugin_prefix();
        add_filter('blockstudio/settings/users/ids', [$this, 'get_blockstudio_user_ids']);
        add_action('init', [$this, 'initialize_blockstudio']);
    }

    /**
     * Sets the plugin prefix dynamically from the file name.
     *
     * @return void
     */
    private function set_plugin_prefix(): void
    {
        $current_file = basename(__FILE__, '.php');  // e.g., 'scanner'
        $this->plugin_prefix = $current_file . '-';  // e.g., 'scanner-'
    }

    /**
     * Retrieves Blockstudio user IDs from environment variables.
     *
     * @return array
     */
    public function get_blockstudio_user_ids(): array
    {
        return defined('WP2_BLOCKSTUDIO_USERS') ? WP2_BLOCKSTUDIO_USERS : [];
    }

    /**
     * Initializes Blockstudio by scanning for valid studio.json files.
     *
     * @return void
     */
    public function initialize_blockstudio(): void
    {
        if (!defined("BLOCKSTUDIO")) {
            error_log('BLOCKSTUDIO is not defined. Exiting initialize_blockstudio.');
            return;
        }

        $valid_directories = $this->get_studio_directories();

        foreach ($valid_directories as $dir) {
            if (class_exists('Blockstudio\Build')) {
                \Blockstudio\Build::init([
                    'dir' => $dir,
                ]);
            } else {
                error_log('Blockstudio\Build class not found.');
            }
        }
    }

    /**
     * Get the list of directories to scan for studios.
     *
     * @return array List of directories.
     */
    private function get_studio_directories(): array
    {
        return [
            __DIR__ . '/blocks/core',
            __DIR__ . '/blocks/mustuse',
        ];
    }

    /**
     * Registers each studio by sanitizing and passing the directory.
     *
     * @return void
     */
    private function register_studios(): void
    {
        $directories = $this->get_studio_directories();

        foreach ($directories as $dir) {
            $this->register_studio(sanitize_text_field($dir));
        }
    }

    /**
     * Register individual studio using Blockstudio Build.
     *
     * @param string $dir Studio directory path.
     * @return void
     */
    private function register_studio(string $dir): void
    {
        $dir = esc_attr($dir);

        // Allow actions to hook into the registration process
        do_action('wp2_register_studio', $dir);

        \Blockstudio\Build::init([
            'dir' => $dir,
        ]);
    }
}