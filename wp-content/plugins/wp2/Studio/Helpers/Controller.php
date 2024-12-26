<?php

namespace WP2\Studio\Helpers;

use WP2\Studio\Helpers\Logger;
use WP2\Studio\Helpers\Profiler;

class Controller
{
    /**
     * Initialize the Studio Manager.
     */
    public function __construct()
    {
        add_action('init', [$this, 'sync_studios']);
    }

    /**
     * Sync all registered studios.
     */
    public function sync_studios()
    {
        Logger::log('Starting studio sync.', 'info');
        Profiler::start('studio_sync');

        $studios = $this->get_registered_studios();
        foreach ($studios as $studio) {
            $this->sync_studio($studio);
        }

        $this->generate_studios_json();

        Profiler::stop('studio_sync');
        Logger::log('Studio sync completed.', 'info');
    }

    /**
     * Sync individual studio.
     */
    private function sync_studio($studio)
    {
        $this->create_or_update_studio_json($studio);
        $this->create_or_update_blocks_json($studio);
    }

    /**
     * Generate or update the studio.json file.
     */
    private function create_or_update_studio_json($studio)
    {
        $studio_json = [
            "identifier" => $studio,
            "title" => ucfirst($studio),
            "description" => "Manages blocks for {$studio}.",
            "alternateName" => '',
        ];

        $studio_json_path = WP_PLUGIN_DIR . '/wp2-studio/' . $studio . '/studio.json';

        if (!file_exists(dirname($studio_json_path))) {
            mkdir(dirname($studio_json_path), 0755, true);
        }

        file_put_contents($studio_json_path, json_encode($studio_json, JSON_PRETTY_PRINT));
        Logger::log("Studio JSON updated for {$studio}", 'info');

        return $studio_json_path;
    }

    /**
     * Create or update block.json files for each block in a studio.
     */
    private function create_or_update_blocks_json($studio)
    {
        $studio_path = WP_PLUGIN_DIR . '/wp2-studio/' . $studio;
        $directories = array_filter(glob($studio_path . '/*'), 'is_dir');

        foreach ($directories as $directory) {
            $this->create_or_update_block_json($directory);
        }
    }

    /**
     * Update individual block.json.
     */
    private function create_or_update_block_json($directory)
    {
        $directory_name = basename($directory);
        $block_json = $directory . '/block.json';

        $required = [
            "\$schema" => "https://app.blockstudio.dev/schema",
            "apiVersion" => 2,
            "name" => 'wp2-' . $directory_name . '/' . $directory_name,
            "blockstudio" => [
                "conditions" => [
                    [
                        [
                            "type" => "postType",
                            "operator" => "==",
                            "value" => "wp2_" . $directory_name,
                        ],
                    ],
                ],
            ],
        ];

        if (file_exists($block_json)) {
            $block = json_decode(file_get_contents($block_json), true);
            $block = array_merge($block, $required);
        } else {
            $default = [
                "title" => ucfirst($directory_name),
                "category" => "studio",
                "icon" => "star-filled",
                "description" => "",
                "keywords" => [],
                "version" => "1.0.0",
            ];
            $block = array_merge($default, $required);
        }

        file_put_contents($block_json, json_encode($block, JSON_PRETTY_PRINT));
        Logger::log("Block JSON updated for {$directory_name}", 'info');
    }

    /**
     * Generate studios.json with all registered studios.
     */
    private function generate_studios_json()
    {
        $studios = $this->get_registered_studios();
        $studio_data = [];

        foreach ($studios as $studio) {
            $studio_json = $this->create_or_update_studio_json($studio);
            $blocks = $this->get_blocks_in_studio($studio);

            $studio_data[] = [
                "identifier" => $studio,
                "title" => ucfirst($studio),
                "description" => "Managing {$studio} blocks.",
                "items" => $blocks,
            ];
        }

        $studios_json_path = WP_PLUGIN_DIR . '/wp2-studio/studios.json';
        file_put_contents($studios_json_path, json_encode($studio_data, JSON_PRETTY_PRINT));

        Logger::log("Generated studios.json.", 'info');
    }

    /**
     * Retrieve all registered studios.
     */
    private function get_registered_studios()
    {
        return array_keys(studio());
    }

    /**
     * Get blocks for a specific studio.
     */
    private function get_blocks_in_studio($studio)
    {
        $studio_path = WP_PLUGIN_DIR . '/wp2-studio/' . $studio;
        $directories = array_filter(glob($studio_path . '/*'), 'is_dir');
        $blocks = [];

        foreach ($directories as $directory) {
            $block_json = $directory . '/block.json';
            if (file_exists($block_json)) {
                $block = json_decode(file_get_contents($block_json), true);
                $blocks[] = [
                    "identifier" => $block['name'],
                    "name" => $block['title'],
                    "description" => $block['description'] ?? '',
                    "raw" => $block,
                ];
            }
        }

        return $blocks;
    }

    /**
     * Reserve blocks by creating posts for each.
     */
    public function reserve_blocks_as_posts()
    {
        $blocks = $this->get_all_blocks();

        foreach ($blocks as $block) {
            $slug = explode('/', $block['name'])[1];
            $type = 'wp2_thing';
            $title = $block['title'];

            $post_data = [
                'post_title' => $title,
                'post_name' => $slug,
                'post_type' => $type,
                'post_status' => 'reserved',
            ];

            $existing_post = get_page_by_path($slug, OBJECT, $type);

            if (!$existing_post) {
                $post_id = wp_insert_post($post_data, true);

                if (is_wp_error($post_id)) {
                    Logger::log("Failed to reserve block post for {$slug}", 'error');
                    continue;
                }

                Logger::log("Reserved post created for {$slug}", 'info');
            }
        }
    }

    /**
     * Get all blocks across all studios.
     */
    private function get_all_blocks()
    {
        $blocks = [];
        $studios = $this->get_registered_studios();

        foreach ($studios as $studio) {
            $blocks = array_merge($blocks, $this->get_blocks_in_studio($studio));
        }

        return $blocks;
    }
}