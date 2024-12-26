<?php

namespace WP2\Integrations\Markerio;

class Init {

    /*
    * Initialize the plugin
    */
    public function __construct() {
        $this->init();
    }

    public static function init() {
        add_action('init', [__CLASS__, 'register_custom_post_types']);
        add_action('rest_api_init', [__CLASS__, 'register_rest_routes']);
        add_filter('mb_settings_pages', [__CLASS__, 'add_settings_page']);
        add_filter('rwmb_meta_boxes', [__CLASS__, 'add_meta_boxes']);
        add_filter('wp_head', [__CLASS__, 'inject_custom_data']);
    }

    public static function register_custom_post_types() {
        $prefix = 'reported_issue_';
        $text_domain = 'wp2s';
        $post_type = 'wp2s_work_issue';
        $meta_key = $post_type;
        $source_tax = 'wp2s_work_issue_source';
        $priority_tax = 'wp2s_work_issue_priority';
        $type_tax = 'wp2s_work_issue_type';

        register_post_type($post_type, [
            'label' => esc_html__('Issues', $text_domain),
            'labels' => [
                'name' => esc_html__('Issues', $text_domain),
                'singular_name' => esc_html__('Issue', $text_domain),
            ],
            'description' => 'The reported issues from various sources, including the webhook from Marker.io',
            'public' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor', 'excerpt', 'custom-fields'],
            'taxonomies' => [$source_tax, $priority_tax, $type_tax],
            'menu_icon' => 'dashicons-flag',
        ]);

        $taxonomies = [
            ['taxonomy' => $prefix . 'priority', 'label' => esc_html__('Priority', $text_domain), 'rest_base' => 'reported-issue-priorities'],
            ['taxonomy' => $prefix . 'type', 'label' => esc_html__('Type', $text_domain), 'rest_base' => 'reported-issue-types'],
        ];

        foreach ($taxonomies as $taxonomy) {
            register_taxonomy($taxonomy['taxonomy'], $post_type, [
                'label' => $taxonomy['label'],
                'show_in_rest' => true,
            ]);
        }

        $statuses = ['open', 'resolved', 'closed', 'archived', 'in_progress', 'blocked', 'reopened', 'in_review'];

        foreach ($statuses as $status) {
            register_post_status($prefix . $status, [
                'label' => ucfirst(str_replace('_', ' ', $status)),
                'public' => true,
            ]);
        }

        register_meta('post', $meta_key, [
            'type' => 'object',
            'single' => true,
            'show_in_rest' => true,
        ]);
    }

    public static function register_rest_routes() {
        $post_type = 'wp2s_work_issue';

        register_rest_route('wp2/v1', '/work/issues', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'handle_issue_submission'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_field($post_type, $post_type, [
            'get_callback' => [__CLASS__, 'fetch_issue_meta'],
        ]);
    }

    public static function handle_issue_submission($request) {
        $prefix = 'wp2s_work_issue_';
        $setting_option = 'wp2s_work_issue_settings';
        $post_type = 'wp2s_work_issue';
        $meta_key = $post_type;

        $headers = $request->get_headers();
        $x_hub_signature = $headers['x_hub_signature'][0] ?? '';

        $signature_token = get_option($setting_option)['signature_token'];
        $signature = explode('=', $x_hub_signature);

        if ($signature[1] !== hash_hmac('sha1', $request->get_body(), $signature_token)) {
            return new \WP_REST_Response(['success' => false, 'message' => 'Invalid Signature'], 401);
        }

        $response_body = json_decode($request->get_body(), true);
        $issue = $response_body['issue'];
        $issue_slug = wp_generate_uuid4();

        $reporter_email = $response_body['reporter']['email'];
        $user = get_user_by('email', $reporter_email);
        $reporter_id = $user ? $user->ID : 0;

        $post_id = wp_insert_post([
            'post_type' => $post_type,
            'post_title' => $issue['title'],
            'post_status' => $prefix . 'open',
            'post_name' => $issue_slug,
            'post_author' => $reporter_id,
            'meta_input' => [
                $meta_key => [
                    'source' => 'webhook',
                    'platform' => 'markerio',
                ],
            ],
        ]);

        return new \WP_REST_Response(['success' => true, 'message' => 'Issue Recorded as Post ID: ' . $post_id], 200);
    }

    public static function fetch_issue_meta($object) {
        $reported_issue = get_post_meta($object['id'], 'wp2s_work_issue', true);
        return $reported_issue;
    }

    public static function add_settings_page($settings_pages) {
        $settings_pages[] = [
            'menu_title' => __('Issues', 'wp2s'),
            'id' => 'wp2s_work_issue_settings',
            'option_name' => 'wp2s_work_issue_settings',
        ];
        return $settings_pages;
    }

    public static function add_meta_boxes($meta_boxes) {
        $meta_boxes[] = [
            'settings_pages' => ['wp2s_work_issue_settings'],
            'fields' => [
                [
                    'name' => __('Signature Token', 'wp2s'),
                    'id' => 'signature_token',
                    'type' => 'text',
                ]
            ],
        ];
        return $meta_boxes;
    }

    public static function inject_custom_data() {
        $customData = [
            'is_home' => is_home(),
            'is_front_page' => is_front_page(),
            'is_admin' => is_admin(),
        ];

        echo "<script>window.markerConfig = Object.assign(window.markerConfig || {}, { customData: " . json_encode($customData) . " });</script>";
    }
}

new Init();