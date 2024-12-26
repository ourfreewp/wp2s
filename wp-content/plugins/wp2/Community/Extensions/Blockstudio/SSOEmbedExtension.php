<?php

WP2\Community\Extensions\Blockstudio;

use WP2\Community\Services\SSOIframeProcessorService;

/**
 * Handles BlockStudio-specific functionality, including modifying embed URLs.
 */
class SSOEmbedExtension
{
    private SSOIframeProcessorService $iframeProcessor;
    private array $embedPaths;
    private static bool $hooksRegistered = false;

    /**
     * Constructor to initialize dependencies and embed paths.
     *
     * @param SSOIframeProcessorService $iframeProcessor Service for processing iframe URLs.
     * @param array $embedPaths Mapping of block names to their embed paths.
     */
    public function __construct(SSOIframeProcessorService $iframeProcessor, array $embedPaths)
    {
        $this->iframeProcessor = $iframeProcessor;
        $this->embedPaths = $embedPaths;
        $this->registerHooks();
    }

    /**
     * Registers hooks for BlockStudio integration.
     */
    private function registerHooks(): void
    {
        if (self::$hooksRegistered) {
            return;
        }

        add_filter('blockstudio/blocks/render', [$this, 'rewriteEmbedUrls'], 10, 4);

        // Log the hook registration
        do_action('qm/debug', 'SSOEmbedExtension: Hook registered for blockstudio/blocks/render');

        self::$hooksRegistered = true;
    }

    /**
     * Rewrites iframe src URLs in the block content with SSO-authenticated URLs.
     *
     * @param string $value Rendered block content.
     * @param object $block Block object containing metadata.
     * @param bool $isEditor Whether the block is rendered in the editor.
     * @param bool $isPreview Whether the block is rendered in preview mode.
     * @return string Modified block content.
     */
    public function rewriteEmbedUrls(string $value, object $block, bool $isEditor, bool $isPreview): string
    {
        // Log when the method is called
        do_action('qm/debug', "rewriteEmbedUrls called for block: {$block->name}");

        // Ensure the block is supported
        if (!isset($this->embedPaths[$block->name])) {
            do_action('qm/debug', "Block not supported: {$block->name}");
            return $value; // Return unmodified content if the block is unsupported
        }

        // Log block processing
        do_action('qm/debug', "Processing block: {$block->name} with embed path: {$this->embedPaths[$block->name]}");

        // Use the iframe processor service to handle rewriting the iframe src attributes
        $processedContent = $this->iframeProcessor->processIframes($value, $block->name, $this->embedPaths);

        // Log the processed content
        do_action('qm/debug', "Processed content for block: {$block->name}");

        return $processedContent;
    }
}
