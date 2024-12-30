<?php

namespace WP2\Run\RewriteRules;

/**
 * Plugin Name: WP2 Rewrite Rules
 * Description: Provides API to manage and flush WordPress rewrite rules.
 */

class Controller
{
    public function __construct()
    {
        add_action('init', [$this, 'register_endpoints']);
    }

    public function register_endpoints()
    {
        add_action('rest_api_init', function () {
            register_rest_route('wp2/v1', '/rewrite-rules', [
                'methods' => 'DELETE',
                'callback' => [$this, 'flush_rewrite_rules'],
                'permission_callback' => [$this, 'verify_request'],
            ]);
        });
    }

    public function flush_rewrite_rules(\WP_REST_Request $request)
    {
        flush_rewrite_rules();
        return ['message' => 'Rewrite rules have been cleared.'];
    }

    public function verify_request(\WP_REST_Request $request)
    {
        if (current_user_can('manage_options')) {
            return true;
        }
        return new \WP_Error('rest_forbidden', __('You do not have permissions to manage rewrite rules.'), ['status' => 403]);
    }
}

new Controller();