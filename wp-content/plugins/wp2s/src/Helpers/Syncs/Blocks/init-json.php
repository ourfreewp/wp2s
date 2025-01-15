<?php
// Path: wp-content/plugins/wp2s/src/Helpers/Syncs/Blocks/init-json.php

namespace WP2S\Helpers\Syncs\Blocks\JSON;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class Controller
{
    private $transient_prefix = 'wp2s_{dir}_blocks';
    private $transient_expiration = HOUR_IN_SECONDS;
    private $qm_debug = true; // Enable or disable Query Monitor logging.

    /**
     * Sync blocks from the specified directory.
     *
     * @param string $directory The directory to scan for block manifests.
     * @param string $dir_identifier A unique identifier for this directory (e.g., 'core', 'custom').
     */
    public function sync_blocks(string $directory, string $dir_identifier)
    {
        $blocks = $this->scan_directory_for_blocks($directory);
        $cache_key = $this->generate_cache_key($dir_identifier);

        // Store all blocks from this directory in a single transient.
        set_transient($cache_key, $blocks, $this->transient_expiration);

        // Log the syncing process to Query Monitor if enabled.
        if ($this->qm_debug) {
            $this->log_debug("Blocks synced for directory: {$dir_identifier}");
            foreach ($blocks as $name => $block) {
                $this->log_debug("Block Name: {$name}");
                $this->log_debug(print_r($block, true));
            }
        }
    }

    /**
     * Scan a directory for block manifests.
     *
     * @param string $directory The directory to scan.
     * @return array An associative array of blocks found in the directory.
     */
    private function scan_directory_for_blocks(string $directory): array
    {
        $blocks = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        $files = new RegexIterator($iterator, '/block\.json$/', RegexIterator::MATCH);

        foreach ($files as $file) {
            $block_data = json_decode(file_get_contents($file->getPathname()), true);

            if (isset($block_data['name'])) {
                $blocks[$block_data['name']] = $block_data;
            }
        }

        return $blocks;
    }

    /**
     * Retrieve all synced blocks for a specific directory.
     *
     * @param string $dir_identifier The unique identifier for the directory.
     * @return array An associative array of blocks for the directory or an empty array if not found.
     */
    public function get_blocks(string $dir_identifier): array
    {
        $cache_key = $this->generate_cache_key($dir_identifier);
        return get_transient($cache_key) ?: [];
    }

    /**
     * Generate a unique cache key for a directory.
     *
     * @param string $dir_identifier The unique identifier for the directory.
     * @return string The generated cache key.
     */
    private function generate_cache_key(string $dir_identifier): string
    {
        return str_replace('{dir}', sanitize_key($dir_identifier), $this->transient_prefix);
    }

    /**
     * Log debug information to Query Monitor if debugging is enabled.
     *
     * @param string $message The message to log.
     */
    private function log_debug(string $message)
    {
        if ($this->qm_debug) {
            do_action('qm/debug', $message);
        }
    }
}