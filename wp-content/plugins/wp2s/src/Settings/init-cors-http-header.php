<?php
// Path: wp-content/plugins/wp2s/Settings/init-cors-http-header.php

namespace WP2S\Settings\CorsHttpHeader;

class Controller {

    private $allowed_origins = [
        'https://www.wp2s.com',
        'https://app.blockstudio.dev',
    ];

    public function __construct() {
        add_action('init', [$this, 'initialize']);
    }

    public function initialize() {
        add_action('send_headers', [$this, 'add_cors_http_header']);
    }

    function add_cors_http_header() {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        $referrer = $_SERVER['HTTP_REFERER'] ?? '';

        // Allow if the origin or referrer is in the allowed list
        if (in_array($origin, $this->allowed_origins) || $this->is_referrer_allowed($referrer)) {
            header("Access-Control-Allow-Origin: " . ($origin ?: $referrer));
        } else {
            header("Access-Control-Allow-Origin: 'null'");  // Block disallowed origins
        }

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");  // If needed for cookies or sessions
    }

    private function is_referrer_allowed($referrer) {
        foreach ($this->allowed_origins as $allowed_origin) {
            if (strpos($referrer, $allowed_origin) === 0) {
                return true;
            }
        }
        return false;
    }
}

new Controller();