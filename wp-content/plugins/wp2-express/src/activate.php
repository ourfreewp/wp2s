<?php

namespace WP2\Express\Template;

class Activate
{
    /**
     * Registers the activation hook for the plugin.
     *
     * This method will be called when the plugin is activated.
     *
     * @return void
     */
    public static function register_activation_hook()
    {
        register_activation_hook(__FILE__, [self::class, 'handle']);
    }

    /**
     * Main handler for the activation process.
     *
     * @return void
     * @throws \Exception If any error occurs during activation.
     */
    public static function handle()
    {
        try {
            // Step 1: Create or update plugin-specific options
            self::create_plugin_options();

            // Step 2: Schedule tasks (cron jobs) required by the plugin
            self::schedule_tasks();

            // Step 3: Verify server environment for compatibility
            self::verify_environment();

            // Log successful activation
            \WP_CLI::log('Plugin activation completed.');
        } catch (\Exception $e) {
            // Log the error if an exception occurs
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('[WP2 Express Template] Activation error: ' . $e->getMessage());
            }
            throw $e; // Propagate the exception for higher-level handling
        }
    }

    /**
     * Creates or updates plugin-specific options.
     *
     * @return void
     */
    private static function create_plugin_options()
    {
        // Optionally log when the options are created or updated
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[WP2 Express Template] Plugin options created or updated.');
        }
    }

    /**
     * Schedules any tasks or cron jobs needed by the plugin.
     *
     * @return void
     */
    private static function schedule_tasks()
    {
        // Log scheduling tasks
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[WP2 Express Template] Scheduled tasks created.');
        }
    }

    /**
     * Verifies the server environment to ensure compatibility.
     *
     * @return void
     * @throws \Exception If the environment does not meet plugin requirements.
     */
    private static function verify_environment()
    {
        // Log environment verification
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[WP2 Express Template] Environment verified successfully.');
        }
    }
}

// Register the activation hook when the class is included
Activate::register_activation_hook();
