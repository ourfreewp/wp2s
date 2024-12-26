<?php

namespace WP2\Health;

class Controller
{
    public function __construct()
    {
        add_action('init', [$this, 'register_option']);
        add_action('init', [$this, 'run_health_checks']);
        $this->register_endpoints();
    }

    public function register_option()
    {
        $health_check = get_option('wp2_health_check');

        if (!$health_check) {
            $health_check_start = [];
            add_option('wp2_health_check', json_encode($health_check_start));
        }
    }

    public function register_endpoints()
    {
        add_action('rest_api_init', function () {
            register_rest_route('wp2/v1', '/health-check', [
                'methods' => 'GET',
                'callback' => [$this, 'get_records'],
                'permission_callback' => [$this, 'verify_request']
            ]);

            register_rest_route('wp2/v1', '/health-check/run', [
                'methods' => 'GET',
                'callback' => [$this, 'run_health_checks'],
                'permission_callback' => [$this, 'verify_request']
            ]);
        });
    }

    public function verify_request(\WP_REST_Request $request)
    {
        $authorization = $request->get_header('Authorization');
        $authorization = explode(' ', $authorization)[1];
        $authorization = base64_decode($authorization);
        [$username, $password] = explode(':', $authorization);

        $user = get_user_by('login', $username);
        $authed_user = wp_authenticate_application_password($user, $username, $password);

        if ($authed_user && user_can($authed_user->ID, 'manage_options')) {
            return new \WP_REST_Response(['success' => 'Permission Callback Success'], 200);
        } else {
            return new \WP_REST_Response(['error' => 'Permission Callback Error'], 400);
        }
    }

    public function get_records(\WP_REST_Request $request)
    {
        $health_check_data = get_option('wp2_health_check');
        return new \WP_REST_Response($health_check_data, 200);
    }

    public function run_health_checks()
    {
        $health_checks = [];
        $health_checks[] = $this->php_version();
        $required_plugin_records = $this->required_plugins();

        foreach ($required_plugin_records as $record) {
            $health_checks[] = $record;
        }

        update_option('wp2_health_check', json_encode($health_checks));
        return $health_checks;
    }

    private function record_meta($record_name)
    {
        $site_name = get_bloginfo('name');
        $site_domain = get_bloginfo('url');
        $record_id = md5($site_domain . '-' . $record_name);

        return [
            'id' => $record_id,
            'site_title' => $site_name,
            'site_domain' => $site_domain,
        ];
    }

    private function php_version()
    {
        $collection = 'site_info';
        [$php_major_version, $php_minor_version] = explode('.', phpversion());
        $expected_php_version = '8.2';
        $php_version_status = ($php_major_version > 8 || ($php_major_version == 8 && $php_minor_version >= 2)) ? 'pass' : 'fail';

        $php_version_record = [
            'name' => 'PHP Version',
            'description' => 'The version of PHP running on the server.',
            'current_value' => phpversion(),
            'expected_value' => $expected_php_version,
            'status' => $php_version_status,
            'collection' => $collection,
        ];

        return array_merge($php_version_record, $this->record_meta('php_version'));
    }

    private function required_plugins()
    {
        $collection = 'plugins';

        if (!function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        $required_plugins = ['block-visibility/block-visibility.php'];
        $plugin_records = [];

        foreach ($required_plugins as $plugin) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
            $plugin_status = is_plugin_active($plugin) ? 'active' : 'inactive';
            $check_status = ($plugin_status == 'active') ? 'pass' : 'fail';

            $plugin_record = [
                'name' => $plugin_data['Name'],
                'description' => $plugin_data['Description'],
                'current_value' => $plugin_status,
                'expected_value' => 'active',
                'status' => $check_status,
                'collection' => $collection,
            ];

            $plugin_records[] = array_merge($plugin_record, $this->record_meta($plugin_data['Name']));
        }

        return $plugin_records;
    }
}

new Controller();
