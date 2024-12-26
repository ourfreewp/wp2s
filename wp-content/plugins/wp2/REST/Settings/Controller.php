<?php

namespace WP2\REST\Settings;

use WP_REST_Response;
use WP_Error;

class Settings_Controller
{
    private $namespace = 'newsplicity/v1';
    private $rest_base = 'settings';

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes()
    {
        register_rest_route($this->namespace, '/' . $this->rest_base, [
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_settings'],
                'permission_callback' => [$this, 'permissions_check'],
            ],
            [
                'methods' => 'POST',
                'callback' => [$this, 'create_setting'],
                'permission_callback' => [$this, 'permissions_check'],
            ],
            [
                'methods' => 'PUT',
                'callback' => [$this, 'update_setting'],
                'permission_callback' => [$this, 'permissions_check'],
            ],
        ]);
    }

    public function get_settings($request)
    {
        $query_params = $request->get_query_params();
        $field_groups = isset($query_params['field_groups']) ? $query_params['field_groups'] : [];

        $options = wp_load_alloptions();
        $settings = [];

        foreach ($options as $option_name => $option_value) {
            if (strpos($option_name, 'newsplicity_') === 0) {
                $settings[] = [
                    'id' => hash('sha256', get_site_url() . '_' . $option_name),
                    'value' => $option_value,
                    'name' => $option_name,
                ];
            }
        }

        return new WP_REST_Response($settings, 200);
    }

    public function create_setting($request)
    {
        $body = json_decode($request->get_body(), true);

        $field_group = $body['field_group'] ?? '';
        $field_name = $body['field_name'] ?? '';
        $field_subgroups = $body['field_subgroups'] ?? [];

        if (empty($field_group) || empty($field_name)) {
            return new WP_REST_Response('Missing required fields', 400);
        }

        $setting_name = $this->generate_setting_name($field_group, $field_name, $field_subgroups);
        if (get_option($setting_name) === false) {
            add_option($setting_name, '');
        }

        $setting_value = get_option($setting_name);
        return new WP_REST_Response([
            'name' => $setting_name,
            'value' => $setting_value,
        ], 201);
    }

    public function update_setting($request)
    {
        $body = json_decode($request->get_body(), true);

        if (!isset($body['newValue'], $body['previousValue'])) {
            return new WP_REST_Response('Invalid request body', 400);
        }

        $new_data = $body['newValue'];
        $option_name = $new_data['name'];
        $option_value = $new_data['value'];

        $updated = update_option($option_name, $option_value);

        if ($updated) {
            return new WP_REST_Response([
                'name' => $option_name,
                'value' => get_option($option_name),
            ], 200);
        } else {
            return new WP_REST_Response('Failed to update setting', 400);
        }
    }

    private function generate_setting_name($field_group, $field_name, $subgroups = [])
    {
        $prefix = 'newsplicity_';
        $subgroup_str = !empty($subgroups) ? '_' . implode('_', $subgroups) : '';
        return $prefix . $field_group . $subgroup_str . '_' . $field_name;
    }

    public function permissions_check()
    {
        return current_user_can('manage_options');
    }
}

// Initialize the controller
new Settings_Controller();