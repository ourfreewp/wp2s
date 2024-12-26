<?php

namespace WP2\REST\Singles\Taxonomies;

class Controller
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    public function register_endpoints()
    {
        register_rest_route('wp2/v1', '/rest-taxonomies', [
            'methods' => 'GET',
            'callback' => [$this, 'get_rest_taxonomies'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ]);
    }

    public function get_rest_taxonomies()
    {
        $taxonomies = get_taxonomies(['show_in_rest' => true], 'objects');
        $formatted_taxonomies = [];

        foreach ($taxonomies as $taxonomy) {
            $formatted_taxonomies[] = [
                "name" => $taxonomy->name,
                "rest_base" => $taxonomy->rest_base ?? $taxonomy->name,
                "rest_controller_class" => $taxonomy->rest_controller_class,
                "site_url" => get_bloginfo('url'),
            ];
        }

        return rest_ensure_response($formatted_taxonomies);
    }
}

new Controller();