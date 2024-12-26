<?php

WP2\Community\Services;

use Firebase\JWT\JWT;
use Exception;

/**
 * Handles JWT creation and decoding.
 */
class JWTService
{
    private string $privateKey;
    private string $algorithm;

    /**
     * Constructor.
     *
     * @throws Exception If required configuration is missing.
     */
    public function __construct()
    {
        $this->privateKey = WP_COMMUNITY_JWT_BETTERMODE_PRIVATE_KEY;
        $this->algorithm = WP_COMMUNITY_JWT_BETTERMODE_ALGORITHM;

        if (empty($this->privateKey)) {
            throw new Exception('JWT private key is not configured.');
        }

        if (empty($this->algorithm)) {
            throw new Exception('JWT algorithm is not configured.');
        }
    }

    /**
     * Generate a JWT token.
     *
     * @param array $payload The payload to encode in the token.
     * @return string The generated JWT token.
     * @throws Exception If the payload is invalid.
     */
    public function createToken(array $payload): string
    {
        $this->validatePayload($payload);

        $payload['iat'] = $payload['iat'] ?? time(); // Issued at
        $payload['exp'] = $payload['exp'] ?? (time() + 3600); // Expires in 1 hour

        try {
            return JWT::encode($payload, $this->privateKey, $this->algorithm);
        } catch (Exception $e) {
            throw new Exception('Error creating JWT: ' . esc_html($e->getMessage()));
        }
    }

    /**
     * Decode and validate a JWT token.
     *
     * @param string $token The JWT token to decode.
     * @return array The decoded payload.
     * @throws Exception If the token is invalid or expired.
     */
    public function decodeToken(string $token): array
    {
        try {
            $decoded = JWT::decode($token, $this->privateKey, [$this->algorithm]);
            return (array) $decoded;
        } catch (Exception $e) {
            throw new Exception('Invalid or expired token: ' . esc_html($e->getMessage()));
        }
    }

    /**
     * Validate the payload structure.
     *
     * @param array $payload The payload to validate.
     * @throws Exception If the payload is invalid.
     */
    private function validatePayload(array $payload): void
    {
        if (empty($payload) || !is_array($payload)) {
            throw new Exception('Payload must be a non-empty array.');
        }
    }
}
