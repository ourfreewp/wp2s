<?php

namespace WP2\REST\Filesystem\Info;

class Controller
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes()
    {
        register_rest_route('wp2/v1', '/filesystem/info', [
            'methods' => 'GET',
            'callback' => [$this, 'get_filesystem_info'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ]);
    }

    public function get_filesystem_info(\WP_REST_Request $request)
    {
        $this->log('Fetching compiled filesystem information.');

        $filesystem_info = $this->compile_filesystem_info();

        if (empty($filesystem_info)) {
            return new \WP_REST_Response($this->handle_error('No filesystem information found.'), 500);
        }

        return new \WP_REST_Response($filesystem_info, 200);
    }

    private function compile_filesystem_info()
    {
        return [
            'storage_used' => disk_total_space('/') - disk_free_space('/'),
            'total_space' => disk_total_space('/'),
            'free_space' => disk_free_space('/'),
            'directory' => get_home_path(),
        ];
    }

    private function log($message)
    {
        error_log('[Filesystem Info] ' . $message);
    }

    private function handle_error($error)
    {
        return new \WP_Error('filesystem_error', $error);
    }
}

new Controller();