<?php

namespace WP2\Studio\Helpers\Studios;

class Controller
{
    private const ASYNC_ACTION = 'wp2_studio_scan';

    public function __construct()
    {
        add_action('current_screen', [$this, 'handle_screen']);
    }

    /**
     * Handle the current screen and schedule async actions when needed.
     *
     * @param \WP_Screen $screen The current admin screen object.
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
     * @param \WP_Screen $screen The current admin screen.
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
            error_log('Async task for studio scan has been scheduled.');
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
            echo '<div class="notice notice-info is-dismissible"><p>Studio scan has been scheduled and will run in the background.</p></div>';
        });
    }
}