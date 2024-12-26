<?php

namespace WP2\Express\Template;

/**
 * Handles plugin deactivation logic.
 */
class Deactivate
{
    /**
     * Registers the deactivation hook.
     *
     * @return void
     */
    public static function register_deactivation_hook()
    {
        // Register the deactivation hook to trigger the handle method
        register_deactivation_hook(__FILE__, [self::class, 'handle']);
    }

    /**
     * Handles the deactivation process.
     * This method will be called when the plugin is deactivated.
     *
     * @return void
     */
    public static function handle()
    {
        try {
            // Disable scheduled tasks (cron jobs) related to the plugin
            self::disable_scheduled_tasks();

            // Clean up transients used by the plugin
            self::clean_up_transients();

            // Optionally, reset plugin-specific settings or options
            self::reset_plugin_settings();

            // Log successful deactivation
            \WP_CLI::log('Plugin deactivated successfully.');
        } catch (\Exception $e) {
            // Log any errors encountered during deactivation
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('[WP2 Express Template] Deactivation error: ' . $e->getMessage());
            }
            \WP_CLI::error('Error during plugin deactivation: ' . $e->getMessage());
        }
    }

    /**
     * Disables any scheduled tasks or cron jobs associated with the plugin.
     *
     * @return void
     */
    private static function disable_scheduled_tasks()
    {
        // Code to clear any scheduled cron jobs or tasks specific to the plugin
        // Example: wp_clear_scheduled_hook('your_plugin_cron_task_name');
    }

    /**
     * Cleans up transient data used by the plugin.
     *
     * @return void
     */
    private static function clean_up_transients()
    {
        // Code to delete any transients specific to the plugin
        // Example: delete_transient('your_plugin_transient_key');
    }

    /**
     * Resets any plugin-specific settings that should be reverted upon deactivation.
     *
     * @return void
     */
    private static function reset_plugin_settings()
    {
        // Code to delete plugin settings or options, such as:
        // delete_option('your_plugin_option_key');
    }
}

// Register deactivation logic when the class is included
Deactivate::register_deactivation_hook();
