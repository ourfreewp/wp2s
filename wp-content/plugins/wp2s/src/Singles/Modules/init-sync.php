<?php
// Path: wp-content/plugins/wp2s/Modules/init-sync.php

namespace WP2S\Modules\Sync;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class Controller
{
    private $modules = [];
    private $post_type = 'wp2s_module';
    private $option_key = 'wp2s_modules';

    public function __construct()
    {
        add_action('load-edit.php', [$this, 'conditional_sync'], 999);
        add_action('init', [$this, 'load_modules'], 999);
        add_action('init', [$this, 'debug_modules'], 999);
    }

    public function conditional_sync()
    {
        if ($this->is_wp2s_module_list_screen()) {
            $this->sync_blocks();
            $this->ensure_posts_exist();
        }
    }

    private function sync_blocks()
    {
        $blocks = [];
        $parent_dir = WP2S_PLUGIN_DIR . 'Modules/';

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

        update_option($this->option_key, $blocks);
    }

    public function load_modules()
    {
        $this->modules = get_option($this->option_key, []);
    }

    private function ensure_posts_exist()
    {
        foreach ($this->modules as $block_name => $block_data) {

            $block_name = str_replace('wp2s/wp2-', '', strtolower($block_name));

            $query = new \WP_Query([
                'post_type'   => $this->post_type,
                'name'        => $block_name,
                'post_status' => 'any',
                'fields'      => 'ids',
            ]);
    
            if (empty($query->posts)) {
                $post_id = wp_insert_post([
                    'post_name'    => $block_name,
                    'post_title'   => ucwords($block_name),
                    'post_status'  => 'draft',
                    'post_type'    => $this->post_type,
                    'meta_input'   => [
                        'wp2_module_name' => $block_name,
                        'wp2_module_data' => $block_data,
                    ],
                ]);
            } else {
                $post_id = wp_update_post([
                    'ID'           => $query->posts[0],
                    'meta_input'   => [
                        'wp2_module_name' => $block_name,
                        'wp2_module_data' => $block_data,
                    ],
                ]);
            }
        }
    }

    public function debug_modules()
    {
        if (function_exists('do_action')) {
            do_action('qm/debug', 'Modules: Initialized');
            do_action('qm/debug', 'Modules: ' . print_r($this->modules, true));
        }
    }

    // Helper to detect wp2s_module list screen
    private function is_wp2s_module_list_screen()
    {
        if (isset($_GET['post_type']) && $_GET['post_type'] === $this->post_type) {
            return true;
        }
        return false;
    }

    public function get_module_data($block_name, $key)
    {
        if (!isset($this->modules[$block_name])) {
            return null;
        }

        $data = $this->modules[$block_name];
        $keys = explode('.', $key);

        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                return null;
            }
            $data = $data[$key];
        }

        return $data;
    }

    public function get_modules()
    {
        return $this->modules;
    }
}

new Controller();