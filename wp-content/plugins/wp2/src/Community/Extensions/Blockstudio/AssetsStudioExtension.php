<?php

WP2\Community\Extensions\Blockstudio;

/**
 * Initializes BlockStudio integration for assets directories.
 */
class AssetsStudioExtension
{
    /**
     * Plugin directory path.
     *
     * @var string
     */
    private string $pluginDir;

    /**
     * Constructor to initialize the class with the plugin directory path.
     */
    public function __construct()
    {
        if (!defined('WP_COMMUNITY_BETTERMODE_PLUGIN_DIR')) {
            throw new \RuntimeException('WP_COMMUNITY_BETTERMODE_PLUGIN_DIR constant is not defined.');
        }

        $this->pluginDir = WP_COMMUNITY_BETTERMODE_PLUGIN_DIR;
    }

    /**
     * Initializes BlockStudio directories for assets.
     *
     * Ensures compatibility with the BlockStudio framework.
     */
    public function init(): void
    {
        add_action('init', function () {
            if (defined('BLOCKSTUDIO')) {
                \Blockstudio\Build::init([
                    'dir' => $this->pluginDir . '/assets',
                ]);
            }
        });
    }
}
