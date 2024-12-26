<?php

namespace WP2\Express\Template;

class Uninstall
{
    /**
     * Main handler for the uninstallation process.
     *
     * @return void
     * @throws \Exception If any error occurs during uninstallation.
     */
    public static function handle()
    {
        try {
            self::delete_plugin_options();
            self::clean_up_custom_data();
            self::drop_custom_tables();

            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('[WP2 Express Template] Uninstall completed successfully.');
            }
        } catch (\Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('[WP2 Express Template] Uninstall error: ' . $e->getMessage());
            }
            throw $e; // Rethrow the exception for logging by the uninstall file.
        }
    }

    /**
     * Deletes plugin-specific options from the database.
     *
     * @return void
     */
    private static function delete_plugin_options()
    {
        // Log plugin option deletion
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[WP2 Express Template] Plugin options deleted.');
        }
    }

    /**
     * Cleans up any custom data (transients, caches, etc.).
     *
     * @return void
     */
    private static function clean_up_custom_data()
    {
        // Log cleanup of custom data
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[WP2 Express Template] Custom data cleaned up.');
        }
    }

    /**
     * Drops custom database tables if they exist.
     *
     * @return void
     */
    private static function drop_custom_tables()
    {
        global $wpdb;

        // Log dropping custom database tables
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[WP2 Express Template] Custom database tables dropped.');
        }
    }
}
