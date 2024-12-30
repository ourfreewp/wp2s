<?php

namespace WP2\REST\Sync;

class Controller
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_sync_endpoint']);
    }

    public function register_sync_endpoint()
    {
        register_rest_route('coda-pack/v26553', '/sync', [
            'methods' => 'POST',
            'callback' => [$this, 'sync_post'],
            'permission_callback' => function () {
                return current_user_can('edit_posts');
            },
        ]);
    }

    public function sync_post($request)
    {
        // Sync logic similar to the original class
    }
}