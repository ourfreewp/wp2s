<?php

namespace WP2\Run\Security;

class Controller
{
    public function verify_request(\WP_REST_Request $request)
    {
        $authorization = $request->get_header('Authorization');

        $authorization = explode(' ', $authorization);
        $authorization = $authorization[1] ?? '';

        $decoded_auth = base64_decode($authorization);
        $auth_parts = explode(':', $decoded_auth);

        if (count($auth_parts) !== 2) {
            return new \WP_REST_Response(['error' => 'Invalid authorization format'], 400);
        }

        [$username, $password] = $auth_parts;
        $user = get_user_by('login', $username);
        $authed_user = wp_authenticate_application_password($user, $username, $password);

        if ($authed_user && user_can($authed_user->ID, 'manage_options')) {
            return new \WP_REST_Response(['success' => 'Permission Callback Success'], 200);
        }

        return new \WP_REST_Response(['error' => 'Permission Callback Error'], 400);
    }
}

new Controller();