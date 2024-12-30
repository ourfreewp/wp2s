<?php

WP2\Community\Services;

use Firebase\JWT\JWT;
use Exception;

/**
 * Service for handling SSO embedding and JWT operations.
 */
class SSOEmbedService
{
    private string $graphqlUrl;
    private string $privateKey;
    private string $algorithm;

    private const DEFAULT_JWT_EXPIRATION = 3600; // 1 hour

    /**
     * Constructor to initialize the service.
     *
     * @param string $graphqlUrl The Bettermode GraphQL endpoint URL.
     * @throws Exception If the JWT configuration is invalid.
     */
    public function __construct(string $graphqlUrl)
    {
        $this->graphqlUrl = rtrim($graphqlUrl, '/');

        $config = $this->loadConfig('jwt.php');

        $this->privateKey = sanitize_text_field($config['private_key'] ?? '');
        $this->algorithm = sanitize_text_field($config['algorithm'] ?? '');

        if (empty($this->privateKey) || empty($this->algorithm)) {
            throw new Exception('Invalid JWT configuration: Missing private_key or algorithm.');
        }
    }

    /**
     * Load a configuration file and validate it.
     *
     * @param string $filename The configuration file name.
     * @return array The configuration data.
     * @throws Exception If the file does not exist or cannot be loaded.
     */
    private function loadConfig(string $filename): array
    {
        $configPath = ABSPATH . 'wp-content/plugins/wp2-community/Config/' . $filename;

        if (!file_exists($configPath)) {
            throw new Exception("Configuration file not found: $configPath");
        }

        $config = require $configPath;

        if (!is_array($config)) {
            throw new Exception("Invalid configuration format in: $configPath");
        }

        return $config;
    }

    /**
     * Generate an SSO URL with a signed JWT token.
     *
     * @param string $baseUrl The base URL to embed.
     * @param array $payload The JWT payload (e.g., user details).
     * @return string The full SSO URL with the token appended.
     * @throws Exception If the JWT token cannot be generated.
     */
    public function generateSsoUrl(string $baseUrl, array $payload): string
    {
        $baseUrl = rtrim($baseUrl, '/');

        try {
            $token = $this->generateJwt($payload);
        } catch (Exception $e) {
            throw new Exception('Failed to generate SSO URL: ' . $e->getMessage());
        }

        return esc_url_raw(add_query_arg(['jwt' => $token], $baseUrl));
    }

    /**
     * Generate a JWT token for SSO.
     *
     * @param array $payload The payload to encode in the token.
     * @return string The generated JWT token.
     * @throws Exception If the JWT token generation fails.
     */
    private function generateJwt(array $payload): string
    {
        if (empty($payload['sub']) || empty($payload['email']) || empty($payload['name'])) {
            throw new Exception('Payload must contain sub, email, and name fields.');
        }

        $payload['iat'] = $payload['iat'] ?? time(); // Issued at
        $payload['exp'] = $payload['exp'] ?? (time() + self::DEFAULT_JWT_EXPIRATION); // Expires in 1 hour

        return JWT::encode($payload, $this->privateKey, $this->algorithm);
    }

    /**
     * Fetch user details from Bettermode via GraphQL.
     *
     * @param string $query The GraphQL query.
     * @param array $variables The query variables.
     * @return array The decoded GraphQL response.
     * @throws Exception If the request fails or returns an error.
     */
    public function fetchGraphqlData(string $query, array $variables = []): array
    {
        $response = wp_remote_post($this->graphqlUrl, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => wp_json_encode([
                'query' => $query,
                'variables' => $variables,
            ]),
        ]);

        if (is_wp_error($response)) {
            throw new Exception('GraphQL request failed: ' . esc_html($response->get_error_message()));
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['errors']) && !empty($body['errors'])) {
            throw new Exception('GraphQL error: ' . esc_html(wp_json_encode($body['errors'])));
        }

        return $body['data'] ?? [];
    }

    /**
     * Sync a WordPress user to Bettermode.
     *
     * @param int $userId The WordPress user ID.
     * @param array $bettermodeData The Bettermode user data to sync.
     * @throws Exception If required data is missing.
     */
    public function syncUser(int $userId, array $bettermodeData): void
    {
        if (empty($bettermodeData['id'])) {
            throw new Exception('Bettermode user data is missing the required "id" field.');
        }

        update_user_meta($userId, 'bettermode_last_sync', time());
        update_user_meta($userId, 'bettermode_data', wp_json_encode($bettermodeData));
        update_user_meta($userId, 'bettermode_sub', sanitize_text_field($bettermodeData['id']));
    }
}
