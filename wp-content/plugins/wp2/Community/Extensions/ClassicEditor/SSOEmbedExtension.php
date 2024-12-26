<?php

WP2\Community\Extensions\ClassicEditor;

use WP2\Community\Services\SSOEmbedService;

class SSOEmbedExtension
{
    private SSOEmbedService $ssoEmbedService;

    public function __construct(SSOEmbedService $ssoEmbedService)
    {
        $this->ssoEmbedService = $ssoEmbedService;
        add_filter('the_content', [$this, 'processIframesInContent'], 10);
        add_filter('content_save_pre', [$this, 'processIframesBeforeSave'], 10);
    }

    public function processIframesInContent(string $content): string
    {
        if (!is_user_logged_in()) {
            return $content;
        }
        return $this->replaceIframeUrls($content);
    }

    public function processIframesBeforeSave(string $content): string
    {
        return $this->replaceIframeUrls($content);
    }

    private function replaceIframeUrls(string $content): string
    {
        $iframePattern = '/<iframe[^>]+src=["\']([^"\']+)["\'][^>]*><\/iframe>/i';

        return preg_replace_callback($iframePattern, function ($matches) {
            $originalUrl = $matches[1];

            try {
                $ssoUrl = $this->ssoEmbedService->generateSSOUrl($originalUrl);
                return str_replace($matches[1], esc_url($ssoUrl), $matches[0]);
            } catch (\Exception $e) {
                error_log('SSO URL generation failed: ' . $e->getMessage());
                return $matches[0];
            }
        }, $content);
    }
}
