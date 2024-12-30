<?php

namespace WP2\Studio\Helpers\Definitions;

class Controller
{
    private const ASYNC_ACTION = 'wp2_definitions_async';
    private const DEFINITIONS_PATH = WP2_EXT_BLOCKSTUDIO_PATH . 'src/Definitions/definitions.json';

    public function __construct()
    {
        add_action('current_screen', [$this, 'handle_screen']);
    }

    /**
     * Handle the current screen and schedule async actions if necessary.
     *
     * @param \WP_Screen $screen The current admin screen.
     * @return void
     */
    public function handle_screen($screen): void
    {
        if ($this->is_studio_list_screen($screen)) {
            $this->schedule_async_action();
        }
    }

    /**
     * Check if the current screen is the WP2 Studio post list screen.
     *
     * @param \WP_Screen $screen
     * @return bool
     */
    private function is_studio_list_screen($screen): bool
    {
        return $screen->id === 'edit-wp2-studio' &&
               isset($_GET['s']) &&
               $_GET['s'] === '';
    }

    /**
     * Schedule the async action if not already scheduled.
     *
     * @return void
     */
    private function schedule_async_action(): void
    {
        if (!$this->is_action_scheduler_available()) {
            error_log("Action Scheduler is not available. Cannot schedule async task.");
            return;
        }

        if (!as_next_scheduled_action(self::ASYNC_ACTION)) {
            as_schedule_single_action(time(), self::ASYNC_ACTION);
            error_log('Async task for processing definitions has been scheduled.');
            $this->show_admin_notice();
        }
    }

    /**
     * Check if Action Scheduler functions are available.
     *
     * @return bool
     */
    private function is_action_scheduler_available(): bool
    {
        return function_exists('as_next_scheduled_action') && function_exists('as_schedule_single_action');
    }

    /**
     * Display an admin notice after scheduling the async task.
     *
     * @return void
     */
    private function show_admin_notice(): void
    {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-info is-dismissible"><p>Definitions processing has been scheduled and will run in the background.</p></div>';
        });
    }

    /**
     * Fetch and return studio definitions from the definitions JSON file.
     *
     * @return array
     */
    public function get_studio_definitions(): array
    {
        $definitions = [];

        if (!file_exists(self::DEFINITIONS_PATH)) {
            error_log("Definitions file not found: " . self::DEFINITIONS_PATH);
            return $definitions;
        }

        $definitions_json = file_get_contents(self::DEFINITIONS_PATH);

        if (!$definitions_json) {
            error_log("Failed to read definitions file: " . self::DEFINITIONS_PATH);
            return $definitions;
        }

        $definitions = json_decode($definitions_json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Failed to parse definitions JSON: " . json_last_error_msg());
            return [];
        }

        return $definitions;
    }
}