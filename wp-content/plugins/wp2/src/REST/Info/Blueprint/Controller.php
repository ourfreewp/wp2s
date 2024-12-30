<?php

namespace WP2\REST\Info\Blueprint;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class Controller to manage REST API template data and settings.
 */
class Controller
{
    public function __construct()
    {
        add_action('admin_init', [$this, 'handle_admin_tasks']);
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Register REST API Endpoints for refreshing and retrieving template data.
     */
    public function register_routes()
    {
        register_rest_route('wp2/v1', '/refresh-template', [
            'methods'  => 'GET',
            'callback' => [$this, 'refresh_template_data'],
            'permission_callback' => [$this, 'permissions_check'],
        ]);

        register_rest_route('newsplicity/v1', '/refresh-data-from-template', [
            'methods'  => 'GET',
            'callback' => [$this, 'refresh_template_data'],
            'permission_callback' => [$this, 'permissions_check'],
        ]);
    }

    /**
     * Handle admin tasks (template refresh and writing to file).
     */
    public function handle_admin_tasks()
    {
        $this->refresh_template_data();
        $this->write_template_data();
    }

    /**
     * Refresh template data from an external API.
     *
     * @return WP_REST_Response
     */
    public function refresh_template_data()
    {
        $response = wp_remote_request(
            'https://template.sites.newsplicity.dev/wp-json/newsplicity/v1/template',
            [
                'method'  => 'GET',
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . base64_encode(rwmb_meta('sync_token', ['object_type' => 'setting'], 'instawp-template')),
                ],
            ]
        );

        if (is_wp_error($response)) {
            return new WP_REST_Response($response->get_error_message(), 400);
        }

        $response_body = wp_remote_retrieve_body($response);
        $decoded_body  = json_decode($response_body, true);

        if (!$decoded_body) {
            return new WP_REST_Response('Failed to decode response.', 400);
        }

        $decoded_body['php_extensions'] = $this->get_php_extension_data();

        $option_saved = update_option('newsplicity_template_data', json_encode($decoded_body));

        if (!$option_saved) {
            return new WP_REST_Response('Failed to save template data.', 400);
        }

        return new WP_REST_Response($decoded_body, 200);
    }

    /**
     * Retrieve PHP extensions and format the data.
     *
     * @return array
     */
    private function get_php_extension_data()
    {
        $php_extensions = get_loaded_extensions();
        return array_map(function ($extension) {
            return ['name' => $extension];
        }, $php_extensions);
    }

    /**
     * Write template data to a JSON file.
     */
    public function write_template_data()
    {
        $template_data = get_option('newsplicity_template_data');

        if (empty($template_data)) {
            return;
        }

        $plugin_dir = plugin_dir_path(__FILE__);
        $file_path  = dirname($plugin_dir) . '/template.json';

        file_put_contents($file_path, $template_data);
    }

    /**
     * Permission callback for the REST API endpoints.
     *
     * @return bool
     */
    public function permissions_check()
    {
        return current_user_can('manage_options');
    }
}

// Initialize the controller
new Controller();