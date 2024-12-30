<?php

namespace WP2\REST\Health;

use WP_REST_Request;
use WP_REST_Response;

/**
 * Class Controller to handle health checks via REST API.
 */
class Controller
{
    private $option_name = 'wp2_health_check_results';
    private $health_checks = [];

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
        add_action('init', [$this, 'run_scheduled_checks']);
        add_action('admin_init', [$this, 'register_options']);
    }

    /**
     * Register REST endpoints for health check results.
     */
    public function register_endpoints()
    {
        register_rest_route('wp2/v1', '/health-check-results', [
            'methods'             => 'GET',
            'callback'            => [$this, 'health_check_results'],
            'permission_callback' => [$this, 'verify_request'],
        ]);
    }

    /**
     * Verify request using custom header.
     */
    public function verify_request(WP_REST_Request $request)
    {
        $headers = $request->get_headers();
        $server_secret = get_option('wp2_health_secret');
        $header_secret = $headers['oh_dear_health_check_secret'][0] ?? null;

        return $header_secret === $server_secret;
    }

    /**
     * Return the health check results.
     */
    public function health_check_results()
    {
        $health_check_response = [
            'finishedAt'   => time(),
            'checkResults' => $this->get_health_checks()
        ];

        return new WP_REST_Response($health_check_response, 200);
    }

    /**
     * Get health check results from options.
     */
    private function get_health_checks()
    {
        $default_checks = [
            [
                'name'                => 'REST API',
                'label'               => 'REST API',
                'status'              => 'ok',
                'notificationMessage' => 'The REST API is available',
                'shortSummary'        => 'Available'
            ],
            [
                'name'                => 'Health Check Results',
                'label'               => 'Health Check Results',
                'status'              => 'ok',
                'notificationMessage' => 'The health check ran successfully',
                'shortSummary'        => 'Healthy'
            ],
        ];

        $stored_results = get_option($this->option_name, []);

        return array_merge($default_checks, $stored_results);
    }

    /**
     * Register default health check option.
     */
    public function register_options()
    {
        if (get_option($this->option_name) === false) {
            add_option($this->option_name, []);
        }
    }

    /**
     * Run recurring scheduled health checks.
     */
    public function run_scheduled_checks()
    {
        $this->schedule_check('check_php_version', 'action_for_check_php_version');
        $this->schedule_check('check_active_theme', 'action_for_check_active_theme');
        $this->schedule_check('check_default_role', 'action_for_check_default_role');

        add_action('action_for_check_php_version', [$this, 'check_php_version']);
        add_action('action_for_check_active_theme', [$this, 'check_active_theme']);
        add_action('action_for_check_default_role', [$this, 'check_default_role']);
    }

    /**
     * Schedule recurring actions if not already scheduled.
     */
    private function schedule_check($action, $hook)
    {
        if (!function_exists('as_has_scheduled_action') || as_has_scheduled_action($hook)) {
            return;
        }

        as_schedule_recurring_action(
            time(),
            300,
            $hook,
            [],
            'wp2_health',
            10
        );
    }

    /**
     * Perform PHP version check.
     */
    public function check_php_version()
    {
        $php_version = phpversion();
        $this->update_check('PHP Version', 'PHP Version', 'ok', 'PHP is up to date', $php_version);
    }

    /**
     * Perform active theme check.
     */
    public function check_active_theme()
    {
        $theme = wp_get_theme();
        $status = $theme->exists() ? 'ok' : 'failed';
        $message = $theme->exists() ? 'Active theme is up to date' : 'Active theme not found';

        $this->update_check('Active Theme', 'Active Theme', $status, $message, $theme->get('Name'));
    }

    /**
     * Perform default role check.
     */
    public function check_default_role()
    {
        $default_role = get_option('default_role');
        $status = ($default_role === 'subscriber') ? 'ok' : 'failed';
        $message = "Default role is set to $default_role";

        $this->update_check('Default Role', 'Default Role', $status, $message, $default_role);
    }

    /**
     * Update health check results.
     */
    private function update_check($name, $label, $status, $message, $summary)
    {
        $check_data = [
            'name'                => $name,
            'label'               => $label,
            'status'              => $status,
            'notificationMessage' => $message,
            'shortSummary'        => $summary
        ];

        $existing_results = get_option($this->option_name, []);
        $existing_results = array_filter($existing_results, function ($check) use ($name) {
            return $check['name'] !== $name;
        });

        $existing_results[] = $check_data;
        update_option($this->option_name, $existing_results);
    }
}