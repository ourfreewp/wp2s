<?php

namespace WP2\REST\Singles\PostTypes;

class Controller
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
        add_action('init', [$this, 'register_custom_post_types']);
    }

    public function register_endpoints()
    {
        register_rest_route('wp2/v1', '/post-types', [
            'methods' => 'GET',
            'callback' => [$this, 'get_post_types'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ]);

        register_rest_route('wp2/v1', '/post-types', [
            'methods' => 'POST',
            'callback' => [$this, 'create_post_type'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ]);

        register_rest_route('wp2/v1', '/post-types', [
            'methods' => 'PUT',
            'callback' => [$this, 'update_post_type'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ]);
    }

    public function get_post_types()
    {
        $post_types = get_post_types();
        $post_types_array = [];

        foreach ($post_types as $post_type) {
            $post_type_object = get_post_type_object($post_type);
            $post_type_object->id = $this->generate_object_id($post_type_object->name, 'post_type');
            $post_types_array[] = $post_type_object;
        }

        return rest_ensure_response($post_types_array);
    }

    public function create_post_type($request)
    {
        $existingPostTypes = $this->get_post_types()->get_data();
        $newPostType = json_decode($request->get_body(), true);
        $newPostTypeId = $this->generate_object_id($newPostType['name'], 'post_type');

        foreach ($existingPostTypes as $existingPostType) {
            if ($existingPostType->id === $newPostTypeId) {
                return new \WP_Error('post_type_exists', 'Post type with this name already exists', ['status' => 400]);
            }
        }

        $existingPostTypes[] = (object)$newPostType;
        update_option('wp2_post_types', json_encode($existingPostTypes));

        return rest_ensure_response($newPostType);
    }

    public function update_post_type($request)
    {
        $request_body = json_decode($request->get_body(), true);

        if (!isset($request_body['newValue'], $request_body['previousValue'])) {
            return new \WP_REST_Response('Invalid request body', 400);
        }

        $updatedValues = $request_body['newValue'];
        $updatedFieldKeys['updatedFields'] = $request_body['updatedFields'];

        $existingPostTypes = json_decode(get_option('wp2_post_types', '[]'), true);
        $postTypeIndex = array_search($updatedValues['id'], array_column($existingPostTypes, 'id'));

        if ($postTypeIndex === false) {
            return new \WP_REST_Response('Post type not found', 404);
        }

        $existingPostTypes[$postTypeIndex] = array_merge($existingPostTypes[$postTypeIndex], $updatedValues);
        update_option('wp2_post_types', json_encode($existingPostTypes));

        return rest_ensure_response($updatedFieldKeys);
    }

    public function register_custom_post_types()
    {
        $custom_post_types = json_decode(get_option('wp2_post_types', '[]'), true);

        foreach ($custom_post_types as $custom_post_type) {
            register_post_type(
                $custom_post_type['name'],
                [
                    'label' => $custom_post_type['name_plural'],
                    'labels' => [
                        'name' => $custom_post_type['name_plural'],
                        'singular_name' => $custom_post_type['name_singular'],
                    ],
                    'description' => $custom_post_type['description'],
                    'public' => filter_var($custom_post_type['public'], FILTER_VALIDATE_BOOLEAN),
                    'hierarchical' => filter_var($custom_post_type['hierarchical'], FILTER_VALIDATE_BOOLEAN),
                    'show_in_rest' => filter_var($custom_post_type['show_in_rest'], FILTER_VALIDATE_BOOLEAN),
                    'rest_base' => $custom_post_type['rest_base'] ?? $custom_post_type['name'],
                    'supports' => $custom_post_type['supports'] ?? ['title', 'editor'],
                    'menu_icon' => $custom_post_type['menu_icon'] ?? 'dashicons-admin-generic',
                ]
            );
        }
    }

    private function generate_object_id($object_name, $object_type, $object_subtype = '')
    {
        $site_url = get_site_url();
        $raw_object_id = str_replace($site_url, '_', $object_name);

        if ($object_type) {
            $raw_object_id .= $object_type;
        }
        if ($object_subtype) {
            $raw_object_id .= $object_subtype;
        }

        return md5($raw_object_id);
    }
}

new Controller();