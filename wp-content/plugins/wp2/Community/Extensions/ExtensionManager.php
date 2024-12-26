<?php

WP2\Community\Extensions;

use WP2\Community\Services\SSOEmbedService;
use WP2\Community\Services\JWTService;

class ExtensionManager
{
    private static ?SSOEmbedService $ssoEmbedService = null;
    private static ?JWTService $jwtService = null;

    private const MANIFEST_RELATIVE_PATH = '/config/extension-manifest.json';

    /**
     * Initialize the extension manager and load extensions from the manifest file.
     *
     * @param string $graphqlUrl The Bettermode GraphQL endpoint URL.
     * @param array $jwtConfig Configuration for JWTService.
     */
    public static function initialize(string $graphqlUrl, array $jwtConfig): void
    {
        try {
            // Initialize services
            self::$ssoEmbedService = new SSOEmbedService($graphqlUrl);
            self::$jwtService = new JWTService($jwtConfig);

            // Resolve manifest path using WP_COMMUNITY_BETTERMODE_PLUGIN_DIR
            $manifestPath = self::resolveManifestPath();

            // Read and decode the manifest file
            $extensions = self::readManifest($manifestPath);
            if (!$extensions) {
                error_log('No extensions loaded due to missing or invalid manifest.');
                return;
            }

            // Load each extension
            foreach ($extensions as $extension) {
                if (self::isExtensionActive($extension)) {
                    self::loadExtension($extension);
                }
            }
        } catch (\Exception $e) {
            error_log('Failed to initialize ExtensionManager: ' . $e->getMessage());
        }
    }

    /**
     * Resolves the manifest path using WP_COMMUNITY_BETTERMODE_PLUGIN_DIR.
     *
     * @return string The resolved manifest file path.
     */
    private static function resolveManifestPath(): string
    {
        return trailingslashit(WP_COMMUNITY_BETTERMODE_PLUGIN_DIR) . ltrim(self::MANIFEST_RELATIVE_PATH, '/');
    }

    /**
     * Reads and decodes the manifest file.
     *
     * @param string $manifestPath The manifest file path.
     * @return array|null The decoded manifest data or null if an error occurred.
     */
    private static function readManifest(string $manifestPath): ?array
    {
        if (!file_exists($manifestPath)) {
            error_log('Extension manifest file not found: ' . $manifestPath);
            return null;
        }

        $fileContents = file_get_contents($manifestPath);

        if ($fileContents === false) {
            error_log('Failed to read extension manifest file: ' . $manifestPath);
            return null;
        }

        $extensions = json_decode($fileContents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('Error decoding extension manifest: ' . json_last_error_msg());
            return null;
        }

        return $extensions;
    }

    /**
     * Check if the plugin dependency for an extension is active.
     *
     * @param array $extension The extension metadata.
     * @return bool True if the dependency is active, false otherwise.
     */
    private static function isExtensionActive(array $extension): bool
    {
        return isset($extension['pluginPath']) && is_plugin_active($extension['pluginPath']);
    }

    /**
     * Load the extension by initializing its class.
     *
     * @param array $extension The extension metadata.
     */
    private static function loadExtension(array $extension): void
    {
        if (!isset($extension['className']) || !class_exists($extension['className'])) {
            error_log('Extension class not found: ' . ($extension['className'] ?? 'Unknown'));
            return;
        }

        $className = $extension['className'];

        try {
            // Match the correct constructor parameters based on the extension's class
            $instance = match ($className) {
                'WP2\Community\Extensions\Blockstudio\SSOEmbedExtension' => new $className(
                    new \WP2\Community\Services\SSOIframeProcessorService(),
                    [
                        'wp2-community/embed-community',
                        'wp2-community/embed-space',
                        'wp2-community/embed-post',
                    ]
                ),
                default => new $className(self::$ssoEmbedService),
            };

            if (method_exists($instance, 'init')) {
                $instance->init();
            }
        } catch (\ArgumentCountError $e) {
            error_log("Error loading extension $className: Incorrect constructor arguments. " . $e->getMessage());
        } catch (\Exception $e) {
            error_log("Error loading extension $className: " . $e->getMessage());
        }
    }
}
