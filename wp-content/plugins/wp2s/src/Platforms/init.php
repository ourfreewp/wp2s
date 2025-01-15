<?php
// Path: wp-content/plugins/wp2s/Platforms/init.php

namespace WP2S\Platforms;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class Controller
{
    private $platforms = [];
    private $post_type = 'wp2s_platform';
    private $option_key = 'wp2s_platforms';

    public function __construct()
    {
        add_action('load-edit.php', [$this, 'conditional_sync']);
        $this->load_platforms();
        add_action('init', [$this, 'init']);
        
    }

    public function init()
    {
        do_action( 'qm/debug', 'Platforms: Initialized' );
    }

    public function conditional_sync()
    {
        $screen = get_current_screen();
        
        if (isset($_GET['post_type']) && $_GET['post_type'] === $this->post_type && $screen->id === 'edit-' . $this->post_type) {
            $this->sync_blocks();
        }
    }

    // Sync block.json files and store them in options table
    private function sync_blocks()
    {
        $blocks = [];
        $parent_dir = dirname(__FILE__);

        // Scan for block.json files in subdirectories
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($parent_dir, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        $files = new RegexIterator($iterator, '/block\.json$/', RegexIterator::MATCH);

        foreach ($files as $file) {
            $block_data = json_decode(file_get_contents($file->getPathname()), true);
            if (isset($block_data['name'])) {
                $blocks[$block_data['name']] = $block_data;
            }
        }

        // Store blocks in options table
        update_option($this->option_key, $blocks);
    }

    // Load platforms from options table
    private function load_platforms()
    {
        $this->platforms = get_option($this->option_key, []);
    }

    // Get specific key from a platform using dot notation
    public function get_platform_data($block_name, $key)
    {
        if (!isset($this->platforms[$block_name])) {
            return null;
        }

        $data = $this->platforms[$block_name];
        $keys = explode('.', $key);

        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                return null;
            }
            $data = $data[$key];
        }

        return $data;
    }

    // Get all platforms
    public function get_platforms()
    {
        return $this->platforms;
    }
}

