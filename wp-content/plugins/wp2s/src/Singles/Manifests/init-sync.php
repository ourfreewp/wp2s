<?php
// Path: wp-content/plugins/wp2s/src/Singles/Manifests/init-sync.php

namespace WP2S\Manifests\Sync;

use WP2S\Helpers\Syncs\Blocks\JSON\Controller as BlockJSONController;
use WP2S\Helpers\Syncs\Blocks\PostTwins\Controller as TwinsController;

class Controller
{
    private $text_domain = 'wp2s';
    private $dir = WP2S_PLUGIN_DIR . 'src/Manifests/';
    private $post_type = 'wp2s_manifest';
    private $dir_identifier = 'manifests';
    private $manifests_controller;

    public function __construct()
    {
        $this->manifests_controller = new BlockJSONController();

        add_action('load-edit.php', [$this, 'conditional_sync'], 999);
        add_action('init', [$this, 'load_items'], 999);
    }

    /**
     * Sync blocks and ensure posts exist when on the item list screen.
     */
    public function conditional_sync()
    {
        if ($this->is_list_screen()) {
            $this->sync_blocks_and_posts();
        }
    }

    /**
     * Sync blocks and ensure posts exist.
     */
    private function sync_blocks_and_posts()
    {
        // Sync blocks.
        $this->sync_blocks();

        // Retrieve synced blocks.
        $items = $this->get_synced_blocks();

        // Ensure posts exist for synced blocks.
        $this->ensure_posts_exist($items);
    }

    /**
     * Sync blocks for the specified directory.
     */
    private function sync_blocks()
    {
        $this->manifests_controller->sync_blocks($this->dir, $this->dir_identifier);
    }

    /**
     * Retrieve synced blocks.
     *
     * @return array
     */
    private function get_synced_blocks(): array
    {
        return $this->manifests_controller->get_blocks($this->dir_identifier);
    }


    /**
     * Ensure posts exist for the given items.
     *
     * @param array $items The items to process.
     */
    private function ensure_posts_exist(array $items)
    {
        TwinsController::check_twin(
            $items,
            $this->post_type,
            // Title callback.
            function ($block_name, $block_data) {
                $block_name = str_replace($this->text_domain . '/', '', $block_name);
                return $block_data['title'] ?? ucwords($block_name);
            },
            // Meta callback.
            function ($block_name, $block_data) {
                return [
                    $this->post_type . '_name' => $block_name,
                    $this->post_type . '_data' => $block_data,
                ];
            },
            // Name callback.
            function ($block_name, $block_data) {
                // Normalize the block name to lowercase.
                $name = strtolower($block_name);

                // Remove the 'wp2s-' prefix.
                $name = str_replace($this->text_domain . '/', '', $name);
                // Return sanitized name.
                return sanitize_title($name);
            },
            // Default status.
            'publish',
            // Post-save callback.
            function ($post_id, $block_name, $block_data) {
                // If category exists in block data, update the taxonomy.
                if (isset($block_data['category']) && !empty($block_data['category'])) {
                    // Use sanitize_text_field to sanitize while retaining case
                    $category = sanitize_text_field($block_data['category']);
                    
                    $current_terms = wp_get_post_terms($post_id, 'wp2s_manifest_category', ['fields' => 'slugs']);
                    if (!in_array($category, $current_terms)) {
                        wp_set_post_terms($post_id, [$category], 'wp2s_manifest_category', false);
                        error_log("Updated wp2s_manifest_category to '{$category}' for post ID {$post_id}");
                    }
                }
            }
        );
    }

    /**
     * Load synced items into the local property.
     */
    public function load_items()
    {
        $this->items = $this->get_synced_blocks();
    }

    /**
     * Check if the current screen is the item list screen.
     *
     * @return bool
     */
    private function is_list_screen(): bool
    {
        $post_type = filter_input(INPUT_GET, 'post_type', FILTER_SANITIZE_STRING);
        return $post_type === $this->post_type;
    }

    /**
     * Get all loaded items.
     *
     * @return array
     */
    public function get_items(): array
    {
        return $this->items ?? [];
    }
}

new Controller();
