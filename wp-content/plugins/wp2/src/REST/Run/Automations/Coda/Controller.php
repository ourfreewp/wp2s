<?php

namespace WP2\Run\Automations\Coda;

class Controller
{
    public function trigger_automation($name, $payload, $args = [])
    {
        if (empty($name)) {
            return new \WP_REST_Response('Missing name', 400);
        }

        if (!empty($args)) {
            $docId  = $args['docId'];
            $ruleId = $args['ruleId'];
            $token  = $args['token'];
        } else {
            $automation = get_posts([
                'name'        => $name,
                'post_type'   => 'wp2docs-automation',
                'post_status' => 'publish',
                'numberposts' => 1
            ])[0];

            $docId  = rwmb_meta('wp2docs_docId', [], $automation->ID);
            $ruleId = rwmb_meta('wp2docs_ruleId', [], $automation->ID);
            $token  = rwmb_meta('wp2docs_token', [], $automation->ID);
            $title  = $automation->post_title;
            $description = $automation->post_excerpt;
        }

        if (empty($docId) || empty($ruleId) || empty($token)) {
            return new \WP_REST_Response('Missing required fields', 400);
        }

        if (empty($payload)) {
            return new \WP_REST_Response('Missing payload', 400);
        }

        $payload = json_encode($payload);

        $response = wp_remote_post(
            'https://coda.io/apis/v1/docs/' . $docId . '/hooks/automation/' . $ruleId,
            [
                'method'  => 'POST',
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
                'body'    => $payload,
            ]
        );

        if (!is_wp_error($response)) {
            $statusCode = $response['response']['code'];
            $message = $response['message'] ?? '';
            $statusMessage = $response['statusMessage'] ?? '';
            $requestId = $response['requestId'] ?? '';

            $description = match ($statusCode) {
                202 => 'Automation triggered successfully.',
                400 => 'Bad request. The request parameters did not conform to expectations.',
                401 => 'Unauthorized. The API token is invalid or has expired.',
                403 => 'Forbidden. The API token does not grant access to this resource.',
                404 => 'Not found. The resource could not be located with the current API token.',
                422 => 'Unprocessable Entity. Unable to process the request.',
                429 => 'Too Many Requests. The client has sent too many requests.',
                500 => 'Internal server error.',
                default => 'Unknown error occurred.'
            };
        } else {
            $statusCode = 500;
            $description = 'Internal server error.';
            $requestId = null;
            $message = $response->get_error_message();
            $statusMessage = '';
        }

        return new \WP_REST_Response([
            'requestId'      => $requestId,
            'message'        => $message,
            'statusCode'     => $statusCode,
            'statusMessage'  => $statusMessage,
            'title'          => $title ?? '',
            'name'           => $name,
            'description'    => $description,
            'payload'        => $payload,
            'created'        => date('Y-m-d\TH:i:s.u\Z'),
        ], 200);
    }

    public function display_automation_actions()
    {
        $output = '';
        $doc_id = rwmb_meta('wp2docs_docId', [], get_the_ID());

        if (!empty($doc_id)) {
            $button_doc = '<button type="button" class="button button-primary" onclick="window.open(\'https://coda.io/d/' . $doc_id . '/\', \'_blank\')">View Doc</button>';
            $button_group = '<div class="wp2docs-settings">' . $button_doc . '</div>';
            $output .= $button_group;
        }

        return '<div class="wp2docs-actions">' . $output . '</div>';
    }
}

new Controller();