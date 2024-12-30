<?php

namespace WP2\Shop\Services\Shopify\Subscriptions;

class Controller
{

    private static $instance;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_sync_customer_tags_endpoint']);
        add_action('plugins_loaded', [$this, 'init_subscriptions']);
        add_action('init', [$this, 'init_hooks']);
    }

    public function init_hooks()
    {
        add_action('init', [$this, 'enqueue_assets'], 999);
        add_action('rest_api_init', [$this, 'register_endpoints']);
        add_action('admin_menu', [$this, 'admin_menus']);
        add_action('plugins_loaded', [$this, 'roles']);
        add_action('plugins_loaded', [$this, 'capabilities']);
    }

    public function enqueue_assets()
    {
        wp_enqueue_script(
            'wp2-shopify-main-js',
            plugins_url('assets/js/main.js', __FILE__),
            [],
            time(),
            true
        );
        wp_enqueue_style(
            'wp2-shopify-styles',
            plugin_dir_url(__FILE__) . 'assets/css/main.css',
            [],
            time()
        );
    }

    public function register_endpoints()
    {
        register_rest_route('shopify/v1', '/subscription', [
            'methods'  => 'POST',
            'callback' => [$this, 'handle_subscription_event'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function handle_subscription_event(\WP_REST_Request $request)
    {
        $body = json_decode($request->get_body(), true);
        $event_type = $body['event'] ?? 'created';
        $customer_email = sanitize_email($body['email'] ?? null);

        if (!$customer_email) {
            return new \WP_REST_Response(['error' => 'Email required'], 400);
        }

        $user = get_user_by('email', $customer_email);
        if (!$user) {
            $user_id = wp_create_user($customer_email, wp_generate_password(), $customer_email);
            if (is_wp_error($user_id)) {
                error_log('Error creating WordPress user: ' . $user_id->get_error_message());
                return new \WP_REST_Response(['error' => 'Failed to create user'], 500);
            }
        } else {
            $user_id = $user->ID;
        }

        update_user_meta($user_id, 'shopify_subscription_status', $event_type);

        return new \WP_REST_Response(['success' => 'Subscription updated'], 200);
    }

    public function admin_menus()
    {
        add_submenu_page(
            'users.php',
            'Customer Tags',
            'Customer Tags',
            'manage_options',
            'edit-tags.php?taxonomy=shopify-customer-tag'
        );
    }

    public function roles()
    {
        add_role('member', 'Member', ['read' => true]);
    }

    public function capabilities()
    {
        $role = get_role('member');
        if ($role) {
            $role->add_cap('read');
        }
    }

    public function sync_customer_tags_callback($request)
    {
        $request_body = json_decode($request->get_body(), true);

        if (empty($request_body['customerId']) || empty($request_body['tags'])) {
            return new \WP_REST_Response(['error' => 'Invalid data'], 400);
        }

        $shopify_customer_id = explode('gid://shopify/Customer/', $request_body['customerId'])[1] ?? null;
        $shopify_customer_tags = sanitize_text_field($request_body['tags']);

        if (!$shopify_customer_id) {
            return new \WP_REST_Response(['error' => 'Invalid Customer ID'], 400);
        }

        $shopify_customer_email = $this->get_shopify_customer_email($shopify_customer_id);

        if (!$shopify_customer_email) {
            return new \WP_REST_Response(['error' => 'Customer email not found'], 404);
        }

        $wordpress_user = $this->get_wordpress_user_by_email($shopify_customer_id, $shopify_customer_email);

        if ($wordpress_user) {
            update_user_meta($wordpress_user->ID, 'wp2_shopify_customer_tags', $shopify_customer_tags);
        }

        return new \WP_REST_Response([
            'status'  => 'success',
            'message' => 'Tags synced successfully.',
        ], 200);
    }

    public function register_sync_customer_tags_endpoint()
    {
        register_rest_route(
            'wp2/v1',
            '/sync-customer-tags',
            [
                'methods'  => 'POST',
                'callback' => [$this, 'sync_customer_tags_callback'],
                'permission_callback' => '__return_true',  // Ensures endpoint is public
            ]
        );
    }

    public function get_shopify_customer_email($shopify_customer_id)
    {
        $shopify_shop_domain  = get_option('wp2_shopify_shop_domain');
        $shopify_storefront_access_token = get_option('wp2_shopify_storefront_access_token');

        if (!$shopify_shop_domain || !$shopify_storefront_access_token) {
            error_log('Missing Shopify domain or token.');
            return null;
        }

        $request_url = "https://{$shopify_shop_domain}/admin/api/2023-07/customers/{$shopify_customer_id}.json";

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $shopify_storefront_access_token,
        ];

        $response = wp_remote_get($request_url, ['headers' => $headers]);

        if (is_wp_error($response)) {
            error_log('Shopify API Error: ' . $response->get_error_message());
            return null;
        }

        $response_body = json_decode(wp_remote_retrieve_body($response));

        return $response_body->customer->email ?? null;
    }

    public function get_wordpress_user_by_email($shopify_customer_id, $shopify_customer_email)
    {
        $wordpress_user = get_user_by('email', $shopify_customer_email);

        if (!$wordpress_user) {
            $wordpress_user_id = wp_create_user($shopify_customer_id, wp_generate_password(), $shopify_customer_email);

            if (is_wp_error($wordpress_user_id)) {
                error_log('Error creating WordPress user: ' . $wordpress_user_id->get_error_message());
                return null;
            }
            return get_user_by('id', $wordpress_user_id);
        }
        return $wordpress_user;
    }
}
