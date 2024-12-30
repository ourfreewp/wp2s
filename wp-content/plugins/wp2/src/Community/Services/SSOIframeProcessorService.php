<?php

WP2\Community\Services;

use WP2\Community\Services\JWTService;
use WP2\Community\Helpers\Urls\SSOUrl;

class SSOIframeProcessorService
{
    private JWTService $jwtService;
    private SSOUrl $ssoUrlHelper;
    private array $supportedBlocks;

    /**
     * Constructor to initialize dependencies and supported blocks.
     *
     * @param JWTService $jwtService Service for generating JWT tokens.
     * @param SSOUrl $ssoUrlHelper Helper for generating SSO URLs.
     * @param array $supportedBlocks List of supported blocks and their embed paths.
     */
    public function __construct(JWTService $jwtService, SSOUrl $ssoUrlHelper, array $supportedBlocks = [])
    {
        $this->jwtService = $jwtService;
        $this->ssoUrlHelper = $ssoUrlHelper;
        $this->supportedBlocks = $supportedBlocks;
    }

    /**
     * Processes iframe src attributes with SSO-authenticated URLs.
     *
     * @param string $content The block's rendered HTML content.
     * @param string $blockName The name of the block being processed.
     * @return string Updated HTML content.
     */

    public function processIframes(string $content, string $blockName, array $supportedBlocks): string
    {
        if (!isset($supportedBlocks[$blockName])) {
            error_log("processIframes: Block not supported: $blockName");
            return $content; // Return original content if block is not supported
        }

        $embedPath = $supportedBlocks[$blockName];
        error_log("processIframes: Embed path for block $blockName: $embedPath");

        try {
            $currentUser = wp_get_current_user();
            if (!$currentUser || 0 === $currentUser->ID) {
                throw new \RuntimeException('Invalid user detected. User must be logged in.');
            }

            $jwtToken = $this->generateJwtToken($currentUser);
            error_log("processIframes: Generated JWT token for user {$currentUser->ID}");

            return $this->replaceIframeSrc($content, $embedPath, $jwtToken);
        } catch (\Exception $e) {
            error_log('Error in processIframes: ' . $e->getMessage());
            return $content; // Return original content on error
        }
    }

    /**
     * Generate a JWT token for the current user.
     *
     * @param \WP_User $currentUser Current WordPress user.
     * @return string JWT token.
     * @throws \Exception If token generation fails.
     */
    private function generateJwtToken(\WP_User $currentUser): string
    {
        return $this->jwtService->createToken([
            'sub' => $currentUser->ID,
            'name' => $currentUser->display_name,
            'email' => $currentUser->user_email,
        ]);
    }

    /**
     * Replace iframe src attributes with SSO-authenticated URLs.
     *
     * @param string $content The block's rendered HTML content.
     * @param string $embedPath Embed path for the block.
     * @param string $jwtToken JWT token for authentication.
     * @return string Updated HTML content.
     */

    private function replaceIframeSrc(string $content, string $embedPath, string $jwtToken): string
    {
        return preg_replace_callback(
            '/<iframe[^>]+src=["\']([^"\']+)["\'][^>]*>/i',
            function ($matches) use ($embedPath, $jwtToken) {
                if (!isset($matches[1])) {
                    error_log("replaceIframeSrc: No src attribute found in iframe.");
                    return $matches[0]; // Return original iframe if no src found
                }

                $originalUrl = $matches[1];
                $ssoUrl = $this->ssoUrlHelper->generateSsoUrl(home_url(), $embedPath, $jwtToken);
                error_log("replaceIframeSrc: Replacing $originalUrl with $ssoUrl");

                return str_replace($originalUrl, esc_url($ssoUrl), $matches[0]);
            },
            $content
        );
    }
}
