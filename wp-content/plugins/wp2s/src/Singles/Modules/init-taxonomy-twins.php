<?php
// Path: wp-content/plugins/wp2s/src/Singles/Modules/init-taxonomy-twins.php

namespace WP2S\Modules\TaxonomyTwins\Sync;

use WP2S\Helpers\Syncs\Blocks\JSON\Controller as BlockJSONController;
use WP2S\Helpers\Syncs\Blocks\TaxonomyTwins\Controller as TaxonomyTwinsController;

class Controller
{
    private $text_domain = 'wp2s';
    private $dir = WP2S_PLUGIN_DIR . 'src/Modules/';
    private $taxonomy = 'wp2s_tax_module';
    private $dir_identifier = 'modules';
    private $block_json_controller;

    public function __construct()
    {
        $this->block_json_controller = new BlockJSONController();

        add_action('load-edit-tags.php', [$this, 'conditional_sync'], 999);
        add_action('init', [$this, 'load_items'], 999);
    }

    /**
     * Sync blocks and ensure taxonomy terms exist when on the term list screen.
     */
    public function conditional_sync()
    {
        if ($this->is_term_list_screen()) {
            $this->sync_blocks_and_terms();
        }
    }

    /**
     * Sync blocks and ensure taxonomy terms exist.
     */
    private function sync_blocks_and_terms()
    {
        // Sync blocks.
        $this->sync_blocks();

        // Retrieve synced blocks.
        $items = $this->get_synced_blocks();

        // Ensure taxonomy terms exist for synced blocks.
        $this->ensure_terms_exist($items);
    }

    /**
     * Sync blocks for the specified directory.
     */
    private function sync_blocks()
    {
        $this->block_json_controller->sync_blocks($this->dir, $this->dir_identifier);
    }

    /**
     * Retrieve synced blocks.
     *
     * @return array
     */
    private function get_synced_blocks(): array
    {
        return $this->block_json_controller->get_blocks($this->dir_identifier);
    }

    /**
     * Ensure taxonomy terms exist for the given items.
     *
     * @param array $items The items to process.
     */
    private function ensure_terms_exist(array $items)
    {
        TaxonomyTwinsController::check_twin(
            $items,
            $this->taxonomy,
            // Title callback.
            function ($block_name, $block_data) {
                $block_title = $block_data['title'] ?? '';
                return $block_data['title'];
            },
            // Meta callback.
            function ($block_name, $block_data) {
                return [
                    $this->taxonomy . '_name' => $block_name,
                    $this->taxonomy . '_data' => $block_data,
                ];
            },
            // Slug callback.
            function ($block_name, $block_data) {
                $name = strtolower($block_name);
                $name = str_replace($this->text_domain . '/wp2-', '', $name);
                return sanitize_title($name);
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
     * Check if the current screen is the term list screen for this taxonomy.
     *
     * @return bool
     */
    private function is_term_list_screen(): bool
    {
        $taxonomy = filter_input(INPUT_GET, 'taxonomy', FILTER_SANITIZE_STRING);
        return $taxonomy === $this->taxonomy;
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