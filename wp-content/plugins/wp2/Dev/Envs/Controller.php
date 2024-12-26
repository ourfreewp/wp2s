<?php
// Path: wp-content/mu-plugins/wp2-dev/helpers/env/init-envs.php

/**
 * WP2\Dev\Helpers\Env class.
 *
 * Handles linking between production and staging sites for WP2 Dev environments.
 *
 * @package WP2\Dev\Helpers
 */

namespace WP2\Dev\Envs;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Controller
{
    /**
     * API key for authentication.
     *
     * @var string
     */
    private $api_key;

    /**
     * URL for linking sites.
     */
    private const LINK_URL = 'https://app.instawp.io/api/v2/connects/link';

    /**
     * Option name for storing staging connection status.
     */
    private const OPTION_NAME = 'wp2_instawp_staging_connection';

    /**
     * Constructor.
     *
     * @param string $api_key API key for authentication.
     */
    public function __construct($api_key)
    {
        if (! defined('WP2_INSTAWP_STAGING_SITE')) {
            return;
        }

        $this->api_key = sanitize_text_field($api_key);
    }

    /**
     * Checks if the staging connection is already established.
     *
     * @return bool True if the connection is established, false otherwise.
     */
    private function is_connection_established()
    {
        return get_option(self::OPTION_NAME) !== false;
    }

    /**
     * Saves the connection status to the WordPress options table.
     *
     * @param array $data Connection data to save.
     * @return void
     */
    private function save_connection_status($data)
    {
        update_option(self::OPTION_NAME, $data);
    }

    /**
     * Links the production site to the staging site using the WordPress REST API.
     *
     * @param string $prod_url The production site URL.
     * @return array|null Response data or null on failure.
     */
    public function link_to_staging($prod_url)
    {
        if ($this->is_connection_established()) {
            error_log('Staging connection is already established.');
            return null;
        }

        $data = [
            'parent_url' => esc_url_raw($prod_url),
            'child_url'  => WP2_INSTAWP_STAGING_SITE,
        ];

        $response = wp_remote_post(self::LINK_URL, [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->api_key,
            ],
            'body'    => wp_json_encode($data),
            'method'  => 'POST',
            'timeout' => 15,
        ]);

        if (is_wp_error($response)) {
            error_log('Error linking sites: ' . $response->get_error_message());
            return null;
        }

        $status_code = wp_remote_retrieve_response_code($response);
        if (200 !== $status_code) {
            error_log('Error linking sites. HTTP status: ' . $status_code);
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $link_result = json_decode($body, true);

        if (! empty($link_result) && ($link_result['status'] ?? false)) {
            $this->save_connection_status($link_result);
            return $link_result;
        }

        error_log('Link failed: ' . ($link_result['message'] ?? 'Unknown error'));
        return null;
    }
}

// Check if required constants are defined before proceeding.
if (
    defined('WP_ENVIRONMENT_TYPE') && 'production' === WP_ENVIRONMENT_TYPE &&
    defined('WP2_INSTAWP_ACCOUNT_API_KEY') && WP2_INSTAWP_ACCOUNT_API_KEY &&
    defined('WP2_INSTAWP_STAGING_SITE') && WP2_INSTAWP_STAGING_SITE
) {

    $api_key = WP2_INSTAWP_ACCOUNT_API_KEY;
    $prod_url = site_url();

    $linker = new Env($api_key);
    $link_result = $linker->link_to_staging($prod_url);

    if ($link_result && isset($link_result['data']['child_connect_id'])) {
        echo esc_html('Link successful: Connect ID ' . $link_result['data']['child_connect_id']) . PHP_EOL;
    } else {
        echo esc_html('Linking failed: Please check the API response or staging configuration.') . PHP_EOL;
    }
}