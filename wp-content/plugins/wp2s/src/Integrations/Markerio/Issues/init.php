<?php

namespace WP2S\Integrations\Markerio\Issues;

class Controller
{
    private $statuses = ['open', 'resolved', 'closed', 'archived', 'in_progress', 'blocked', 'reopened', 'in_review'];
    private $rest_namespace = 'wp2/v1';
    private $rest_path = '/markerio/issues';
    private $issue_source = 'markerio';
    private $issue_platform = 'wordpress';
    private $post_type = 'wp2s_issue';
    private $prefix = 'wp2s_issue_';
    private $meta_key = 'wp2s_issue_reported';
    private $textdomain = 'wp2s';

    public function __construct()
    {
        add_action('init', function () {
            do_action( 'qm/debug', 'Issues Reported Controller Initialized' );
        }, 999);

        add_action('init', [$this, 'register_meta']);
        add_action('init', [$this, 'register_post_statuses']);
        add_action('init', [$this, 'register_rest_routes'], 999);
        add_action('init', [$this, 'handle_issue_submission']);
        add_action('wp_footer', [$this, 'inject_custom_data']);
    }

    public function register_meta()
    {
        $meta_key = $this->meta_key;

        register_meta('post', $meta_key, [
            'type' => 'object',
            'single' => true,
            'show_in_rest' => true,
        ]);
    }

    public function register_post_statuses()
    {
        foreach ($this->statuses as $status) {
            register_post_status($this->prefix . $status, [
                'label' => ucfirst(str_replace('_', ' ', $status)),
                'public' => true,
            ]);
        }
    }

    public function register_rest_routes()
    {
        register_rest_route($this->rest_namespace, $this->rest_path, [
            'methods' => 'POST',
            'callback' => [$this, 'handle_issue_submission'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_field($this->post_type, $this->post_type, [
            'get_callback' => [$this, 'fetch_issue_meta'],
        ]);
    }

    public function handle_issue_submission($request)
    {
        $headers = $request->get_headers();
        $x_hub_signature = $headers['x_hub_signature'][0] ?? '';

        $signature_token = defined('WP2_MARKERIO_SIGNATURE_TOKEN') ? WP2_MARKERIO_SIGNATURE_TOKEN : '';

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
            'post_type' => $this->post_type,
            'post_title' => $issue['title'],
            'post_status' => 'draft',
            'post_name' => $issue_slug,
            'post_author' => $reporter_id,
            'meta_input' => [
                $this->meta_key => [
                    'source' => $this->issue_source,
                    'platform' => $this->issue_platform,
                ],
            ],
        ]);

        return new \WP_REST_Response(['success' => true, 'message' => 'Issue Recorded as Post ID: ' . $post_id], 200);
    }

    public function fetch_issue_meta($object)
    {
        return get_post_meta($object['id'], $this->meta_key, true);
    }

    public function inject_custom_data()
    {
        $customData = [
            'is_home' => is_home(),
            'is_front_page' => is_front_page(),
            'is_admin' => is_admin(),
            'is_single' => is_single(),
            'is_page' => is_page(),
            'is_archive' => is_archive(),
            'is_search' => is_search(),
            'is_404' => is_404(),
            'is_attachment' => is_attachment(),
            'is_singular' => is_singular(),
            'is_category' => is_category(),
            'is_tag' => is_tag(),
            'is_tax' => is_tax(),
            'is_author' => is_author(),
            'is_date' => is_date(),
            'is_year' => is_year(),
            'is_month' => is_month(),
            'is_day' => is_day(),
            'is_time' => is_time(),
            'is_feed' => is_feed(),
            'is_comment_feed' => is_comment_feed(),
            'is_trackback' => is_trackback(),
            'is_embed' => is_embed(),
            'is_paged' => is_paged(),
            'is_admin_bar_showing' => is_admin_bar_showing(),
            'is_customize_preview' => is_customize_preview(),
            'is_preview' => is_preview(),
            'is_robots' => is_robots(),
            'is_favicon' => is_favicon(),
            'is_user_logged_in' => is_user_logged_in(),
            'is_blog_admin' => is_blog_admin(),
            'is_network_admin' => is_network_admin(),
            'is_user_admin' => is_user_admin(),
            'is_multisite' => is_multisite(),
            'is_super_admin' => is_super_admin(),
        ];

        $customData = array_filter($customData);

        echo "<script>window.markerConfig = Object.assign(window.markerConfig || {}, { customData: " . json_encode($customData) . " });</script>";
    }
}

$controller = new Controller();