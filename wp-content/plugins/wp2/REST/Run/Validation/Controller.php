<?php

namespace WP2\Run\Validation;

class Controller
{
    public function sanitize_value(\WP_REST_Request $request)
    {
        $params = $request->get_params();
        $body = json_decode($request->get_body(), true);

        if (!isset($params['type'])) {
            return new \WP_Error('missing_type', 'Missing type parameter', ['status' => 400]);
        }

        if (!isset($body['initial_value'])) {
            return new \WP_Error('missing_value', 'Missing value parameter', ['status' => 400]);
        }

        $type = $params['type'];
        $initial_value = $body['initial_value'];
        $sanitized_value = null;

        $sanitized_value = match ($type) {
            'title', 'slug' => sanitize_title($initial_value),
            'key' => sanitize_key($initial_value),
            'url' => sanitize_url($initial_value),
            'meta' => sanitize_meta($initial_value),
            'post' => sanitize_post($initial_value),
            'term' => sanitize_term($initial_value),
            'user' => sanitize_user($initial_value),
            'email' => sanitize_email($initial_value),
            'option' => sanitize_option($initial_value),
            'file_name' => sanitize_file_name($initial_value),
            'hex_color' => sanitize_hex_color($initial_value),
            'mime_type' => sanitize_mime_type($initial_value),
            'html_class' => sanitize_html_class($initial_value),
            'int' => intval($initial_value),
            'float' => floatval($initial_value),
            'bool' => boolval($initial_value),
            'json' => json_decode($initial_value),
            'array' => (array) $initial_value,
            'object' => (object) $initial_value,
            'csv' => explode(',', $initial_value),
            'csv_int' => array_map('intval', explode(',', $initial_value)),
            'csv_email' => array_map('sanitize_email', explode(',', $initial_value)),
            'csv_url' => implode(',', array_map('esc_url_raw', explode(',', $initial_value))),
            default => new \WP_REST_Response([
                'error' => 'unsupported_type',
                'message' => 'Unsupported type parameter'
            ], 400)
        };

        $response_data = [
            'data' => [
                'initial_value' => $initial_value,
                'sanitized_value' => $sanitized_value,
                'type' => $type
            ]
        ];

        return new \WP_REST_Response($response_data, 200);
    }
}