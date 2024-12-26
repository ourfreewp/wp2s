<?php

namespace WP2\Studio\Plugins;

use WP_Error;

class Controller
{
    private const PLUGIN_PREFIX = 'wp2-';
    private const EXCLUDED_ZONES = ['wp2-studio'];

    public function __construct()
    {
        add_action('init', [$this, 'initialize']);
    }

    /**
     * Initialize the plugin and trigger Blockstudio build process.
     *
     * @return void
     */
    public function initialize(): void
    {
        if (!defined('BLOCKSTUDIO')) {
            error_log('BLOCKSTUDIO constant is not defined. Ensure the Blockstudio plugin is properly initialized.');
            return;
        }

        if (!class_exists('Blockstudio\Build')) {
            error_log('Blockstudio\Build class does not exist. Ensure the Blockstudio plugin is active and properly loaded.');
            return;
        }

        $this->initialize_blockstudio_plugins();
    }

    /**
     * Initialize Blockstudio plugins by iterating through active plugins.
     *
     * @return void
     */
    private function initialize_blockstudio_plugins(): void
    {
        $plugins = $this->get_wp2_plugins();

        foreach ($plugins as $plugin_file => $plugin_data) {
            $plugin_dir = WP_PLUGIN_DIR . '/' . dirname($plugin_file);

            \Blockstudio\Build::init([
                'dir' => $plugin_dir . '/src',
            ]);

            error_log("Initialized Blockstudio for plugin: {$plugin_file}");
        }
    }

    /**
     * Retrieve all active WP2 plugins, excluding specific zones.
     *
     * @return array
     */
    private function get_wp2_plugins(): array
    {
        $all_plugins = get_plugins();

        return array_filter($all_plugins, function ($plugin_data, $plugin_file) {
            return $this->is_wp2_plugin_active($plugin_file);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Check if a plugin matches the WP2 prefix and is active, excluding certain zones.
     *
     * @param string $plugin_file The plugin file path.
     * @return bool
     */
    private function is_wp2_plugin_active(string $plugin_file): bool
    {
        if (strpos($plugin_file, self::PLUGIN_PREFIX) !== 0 || !is_plugin_active($plugin_file)) {
            return false;
        }

        foreach (self::EXCLUDED_ZONES as $excluded_zone) {
            if (strpos($plugin_file, $excluded_zone) !== false) {
                return false;
            }
        }

        return true;
    }
}