<?php

namespace WP2\REST\Autocomplete\Fields;

use WP_REST_Request;
use WP_Error;

class Controller
{
    private $namespace = 'wp2/v1';
    private $route = 'autocomplete/fields';
    private $textdomain = 'wp2s';
    private $prefix = 'wp2s_';

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_routes'], 60);
    }

    public function register_routes()
    {
        register_rest_route($this->namespace, '/' . $this->route, [
            'methods'             => 'GET',
            'callback'            => [$this, 'get_post_type_fields'],
            'permission_callback' => [$this, 'check_permissions'],
            'args'                => [
                'post_type' => [
                    'required'          => true,
                    'validate_callback' => [$this, 'validate_post_type'],
                ],
            ],
        ]);
    }

    public function get_post_type_fields(WP_REST_Request $request)
    {
        $post_type = $request->get_param('post_type');

        // Attempt to resolve post type by rest_base
        $resolved_post_type = $this->lookup_post_type_by_rest_base($post_type);

        if (is_wp_error($resolved_post_type)) {
            error_log("Post type not found for rest_base: $post_type");
            return rest_ensure_response([]);  // Return empty array on failure (optional)
        }

        // Fetch fields for the resolved post type
        $fields = rwmb_get_object_fields($resolved_post_type);

        if (empty($fields)) {
            return new WP_Error('no_fields', __('No fields found for this post type', $this->textdomain), ['status' => 404]);
        }

        $field_names = array_map(function ($field) {
            return $field['id'];
        }, $fields);

        return rest_ensure_response($field_names);
    }

    /**
     * Lookup post type by matching the REST base path.
     */
    public function lookup_post_type_by_rest_base($rest_path)
    {
        $post_types = get_post_types([], 'objects');

        error_log('Checking post types during lookup: ' . print_r(array_keys($post_types), true));

        foreach ($post_types as $post_type => $object) {
            $rest_base = isset($object->rest_base) ? $object->rest_base : $post_type;
            if ($rest_path === $rest_base) {
                error_log("Post type found for rest_base: $post_type");
                return $post_type;
            }
        }

        error_log("Invalid post type or rest_base: $rest_path");
        return new WP_Error(
            'invalid_post_type',
            __('Invalid post type REST path.', $this->textdomain),
            ['status' => 400]
        );
    }

    /**
     * Validate post type during REST argument processing.
     */
    public function validate_post_type($post_type, $request, $param)
    {
        $resolved_post_type = $this->lookup_post_type_by_rest_base($post_type);

        if (is_wp_error($resolved_post_type)) {
            return false;
        }

        return true;
    }

    /**
     * Allow access to all authenticated users.
     */
    public function check_permissions(WP_REST_Request $request)
    {
        return true;  // Permit access for now
    }

    /**
     * Helper to get site by domain for multisite.
     */
    private function get_site_by_domain($domain)
    {
        $sites = get_sites([
            'domain' => $domain,
            'number' => 1
        ]);

        return !empty($sites) ? $sites[0] : false;
    }
}
