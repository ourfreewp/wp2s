<?php

WP2\Community\Helpers\Urls;

/**
 * Helper class for generating SSO URLs.
 */
class SSOUrl
{
    /**
     * Generate an SSO-authenticated URL.
     *
     * @param string $baseUrl The base URL of the application.
     * @param string $path The embed path.
     * @param string $jwtToken The JWT token to append as a query parameter.
     * @return string The fully constructed SSO URL.
     */
    public function generateSsoUrl(string $baseUrl, string $path, string $jwtToken): string
    {
        // Construct the full URL
        $fullUrl = trailingslashit($baseUrl) . ltrim($path, '/');

        // Add the JWT token as a query parameter
        return add_query_arg('token', $jwtToken, $fullUrl);
    }
}
