<?php

namespace WP2\REST\Singles\Items;

class Controller
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    public function register_endpoints()
    {
        register_rest_route('coda-pack/v26553', '/item', [
            'methods' => 'GET',
            'callback' => [$this, 'get_item'],
            'permission_callback' => function () {
                return current_user_can('edit_posts');
            },
        ]);
    }

    public function get_item($request)
    {
        $url = sanitize_text_field($request->get_param('url'));
        $type = sanitize_text_field($request->get_param('type'));

        $post_type = get_post_type_object($type);
        if (!$post_type) {
            return new \WP_Error('invalid_post_type', 'Invalid post type', ['status' => 400]);
        }

        $existing_meta = $this->find_by_meta($url, 'post', $post_type->name);
        if (!$existing_meta) {
            return new \WP_Error('not_found', 'No content found', ['status' => 404]);
        }

        return rest_ensure_response([
            'ID' => $existing_meta->ID,
            'title' => get_the_title($existing_meta->ID),
            'type' => $post_type->name,
            'url' => $url,
        ]);
    }

    private function find_by_meta($url, $object_type, $post_type)
    {
        $query = new \WP_Query([
            'meta_query' => [
                [
                    'key' => '_oddnewsshow_url',
                    'value' => $url,
                ],
            ],
            'post_type' => $post_type,
            'posts_per_page' => 1,
        ]);

        return $query->have_posts() ? $query->posts[0] : false;
    }
}

new ItemController();