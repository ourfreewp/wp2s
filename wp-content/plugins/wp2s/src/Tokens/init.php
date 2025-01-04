<?php
namespace WPS2\Programs\Membership\Tokens;

class Controller {

    private $textdomain = 'wp2s';
    private $type       = 'wp2s_token';

    public function extend_post_type() {
        add_action('save_post', [$this, 'enforce_token_limit'], 10, 3);
    }
    
    public function check_token_limit() {
        $maxTokens = defined('WP2_TOKENS_MAX') ? WP2_TOKENS_MAX : 0;

        if ($maxTokens <= 0) {
            return true;
        }

        $args = [
            'post_type'   => $this->type,
            'post_status' => 'publish',
            'fields'      => 'ids',
            'posts_per_page' => -1,
        ];

        $existing_tokens = get_posts($args);
        $total_tokens = count($existing_tokens);

        if ($total_tokens >= $maxTokens) {
            return new \WP_Error(
                'token_limit_reached',
                __('Maximum token limit reached for this user.', $this->textdomain),
                ['status' => 403]
            );
        }

        return true;
    }

    public function enforce_token_limit($post_id, $post, $update) {
        if ($post && $post->post_type === $this->type && !$update) {
            $result = $this->check_token_limit();

            if (is_wp_error($result)) {
                wp_die($result->get_error_message(), __('Error', $this->textdomain), ['response' => 403]);
            }
        }
    }
}


$controller = new Controller();
$controller->extend_post_type();
