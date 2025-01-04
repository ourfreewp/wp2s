<?php
namespace WPS2\Programs\Membership\Tokens\REST;

class Controller {

    public function __construct() {
        $this->init();
    }

    public function init() {
        add_action('rest_api_init', [$this, 'register_routes']);
        add_filter('rest_post_query', [$this, 'filter_tokens_by_author'], 10, 2);
    }

    /**
     * Register custom REST routes for tokens.
     */
    public function register_routes() {
        register_rest_route('wp2/v1', '/tokens/system', [
            'methods' => ['GET'],
            'callback' => [$this, 'handle_token_request'],
            'permission_callback' => [$this, 'permissions_check'],
        ]);
    }

    /**
     * Handle GET requests for the tokens endpoint.
     */
    public function handle_token_request($request) {
        // Use constants from wp-config.php with fallbacks
        $maxTokens = defined('WP2_TOKENS_MAX') ? WP2_TOKENS_MAX : 0;
        $tokenSetName = defined('WP2_TOKENS_NAME') ? WP2_TOKENS_NAME : 'WP2S';
        $tokenId = defined('WP2_TOKENS_ID') ? WP2_TOKENS_ID : 'wp2s';

        if (!defined('WP2_TOKENS_MAX')) {
            error_log('WP2_TOKENS_MAX is not defined in wp-config.php');
        }

        $counts = wp_count_posts('wp2s_token');

        $total_tokens = 0;

        foreach ($counts as $status => $count) {
            $total_tokens += $count;
        }

        // Build response data
        $data = [
            'max_tokens' => $maxTokens,
            'token_set_name' => $tokenSetName,
            'token_set_id' => $tokenId,
            'total_tokens' => $total_tokens,
        ];

        // Return as JSON response
        return new \WP_REST_Response($data, 200);
    }


    /**
     * Filter REST API queries to restrict tokens by author.
     */
    public function filter_tokens_by_author($args, $request) {
        // Apply only to wp2s_token post type REST requests
        if (isset($request['post_type']) && $request['post_type'] === 'wp2s_token') {
            $args['author'] = get_current_user_id();
        }
        return $args;
    }

    /**
     * Permissions check for accessing the tokens endpoint.
     */
    public function permissions_check($request) {
        return current_user_can('manage_options');
    }
}

$controller = new Controller();