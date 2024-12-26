<?php

namespace WP2\REST\Singles\Settings;

use WP_REST_Request;
use WP_REST_Response;

/**
 * Class Controller to handle custom settings via REST API.
 */
class Controller
{
    private $option_prefix = 'newsplicity_';

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    /**
     * Register REST API Endpoints.
     */
    public function register_endpoints()
    {
        register_rest_route('wp2/v1', '/settings', [
            'methods'  => 'GET',
            'callback' => [$this, 'get_settings'],
            'permission_callback' => [$this, 'check_permissions'],
        ]);

        register_rest_route('wp2/v1', '/settings', [
            'methods'  => 'POST',
            'callback' => [$this, 'create_setting'],
            'permission_callback' => [$this, 'check_permissions'],
        ]);

        register_rest_route('wp2/v1', '/settings', [
            'methods'  => 'PUT',
            'callback' => [$this, 'update_setting'],
            'permission_callback' => [$this, 'check_permissions'],
        ]);
    }

    /**
     * Check permissions for REST API access.
     */
    public function check_permissions()
    {
        return current_user_can('manage_options');
    }

    /**
     * Handle GET request for retrieving settings.
     */
    public function get_settings(WP_REST_Request $request)
    {
        $query_params = $request->get_query_params();
        $field_groups = isset($query_params['field_groups']) ? $query_params['field_groups'] : [];

        $options = wp_load_alloptions(true);
        $settings = [];

        foreach ($options as $name => $value) {
            if (strpos($name, $this->option_prefix) === 0) {
                $settings[] = $this->format_setting($name, $value);
            }
        }

        if (!empty($field_groups)) {
            $settings = array_filter($settings, function ($setting) use ($field_groups) {
                return in_array($setting['field_group'], $field_groups);
            });
        }

        return new WP_REST_Response($settings, 200);
    }

    /**
     * Handle POST request for creating new settings.
     */
    public function create_setting(WP_REST_Request $request)
    {
        $data = $request->get_json_params();

        $field_group = $data['field_group'] ?? '';
        $field_name = $data['field_name'] ?? '';
        $field_subgroups = $data['field_subgroups'] ?? [];

        if (!$field_group || !$field_name) {
            return new WP_REST_Response('Missing required fields', 400);
        }

        $setting_name = $this->create_setting_name($field_group, $field_name, $field_subgroups);
        add_option($setting_name, '');

        $value = get_option($setting_name);
        return new WP_REST_Response($this->format_setting($setting_name, $value), 201);
    }

    /**
     * Handle PUT request for updating settings.
     */
    public function update_setting(WP_REST_Request $request)
    {
        $data = $request->get_json_params();

        $new_value = $data['newValue'] ?? null;
        $option_name = $new_value['name'] ?? null;
        $option_value = $new_value['value'] ?? null;

        if (!$option_name || $option_value === null) {
            return new WP_REST_Response('Invalid request body', 400);
        }

        $updated = update_option($option_name, $option_value);
        if ($updated) {
            return new WP_REST_Response(['value' => $option_value, 'name' => $option_name], 200);
        }

        return new WP_REST_Response('Failed to update setting', 400);
    }

    /**
     * Generate setting name based on field group and subgroups.
     */
    private function create_setting_name($field_group, $field_name, $subgroups = [])
    {
        $subgroup_str = !empty($subgroups) ? '_' . implode('_', $subgroups) : '';
        return $this->option_prefix . "{$field_group}{$subgroup_str}_{$field_name}";
    }

    /**
     * Format settings into an array with details.
     */
    private function format_setting($name, $value)
    {
        $meta = $this->get_setting_meta($name);
        $is_private = $meta['private'] ?? false;

        $value = $is_private ? substr($value, 0, 3) . '********' : $value;

        $key_parts = explode('_', $name);
        $field_group = $key_parts[1] ?? 'general';
        $field_name = end($key_parts);
        $subgroups = array_slice($key_parts, 2, -1);

        return [
            'title'       => $meta['title'] ?? $name,
            'description' => $meta['description'] ?? '',
            'field_group' => $field_group,
            'field_subgroups' => $subgroups,
            'field_name'  => $field_name,
            'private'     => $is_private,
            'name'        => $name,
            'value'       => $value,
            'id'          => hash('sha256', get_site_url() . '_' . $name),
        ];
    }

    /**
     * Get meta information about a setting.
     */
    private function get_setting_meta($name)
    {
        $fields = $this->default_fields();
        foreach ($fields as $field) {
            if ($field['name'] === $name) {
                return $field;
            }
        }
        return [];
    }

    /**
     * Default site fields for custom settings.
     */
    private function default_fields()
    {
        $prefix = $this->option_prefix;
        return [
            ['name' => $prefix . 'klaviyo_api_privateKey', 'title' => 'Klaviyo Private API Key', 'private' => true],
            ['name' => $prefix . 'klaviyo_api_publicKey', 'title' => 'Klaviyo Public API Key', 'private' => true],
        ];
    }
}