<?php
// Path: wp-content/plugins/wp2/Singles/Block/Controller.php

namespace WP2\Singles\Block;

use InvalidArgumentException;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Controller
{
    private array $dirs = [];
    private array $block_type_dirs = [];
    private array $block_lib_dirs = [];

    public function __construct()
    {   
        $this->add_scan_directory(WP2_PATH . 'src');
        add_action('init', [$this, 'init']);
        $this->initialize();
    }

    public function init()
    {
        do_action('qm/debug', 'Block Controller');
    }

    private function initialize(): void
    {
        $this->scan_for_block_types();
        $this->initialize_block_libraries();
    }

    public function add_scan_directory(string $dir): void
    {
        $sanitized_dir = sanitize_text_field($dir);
        
        if (is_dir($sanitized_dir)) {
            $this->dirs[] = rtrim($sanitized_dir, '/');
        } else {
            throw new InvalidArgumentException(sprintf(
                'Invalid directory for scanning: %s',
                esc_html($sanitized_dir)
            ));
        }
    }

    private function scan_for_block_types(): void
    {
        foreach ($this->dirs as $dir) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
    
            foreach ($iterator as $item) {
                error_log('Scanning: ' . $item->getPathname());
    
                if ($item->isDir()) {
                    $manifest_path = $item->getPathname() . '/block-manifest.json';
                    if (file_exists($manifest_path)) {
                        error_log('Found manifest in: ' . $item->getPathname());
                        $this->block_type_dirs[] = $item->getPathname();
                        $this->scan_for_block_libraries($item->getPathname());
                    }
                }
            }
        }
    }

    private function scan_for_block_libraries(string $block_type_dir): void
    {
        $block_lib_dirs = glob($block_type_dir . '/*', GLOB_ONLYDIR) ?: [];

        foreach ($block_lib_dirs as $block_lib_dir) {
            $this->block_lib_dirs[] = $block_lib_dir;
        }
    }

    private function initialize_block_libraries(): void
    {
        foreach ($this->block_lib_dirs as $block_lib_dir) {
            $this->initialize_studio($block_lib_dir);
        }
    }

    private function initialize_studio(string $block_lib_dir): void
    {
        if (defined('BLOCKSTUDIO') && is_dir($block_lib_dir)) {
            \Blockstudio\Build::init([
                'dir' => trailingslashit(esc_url_raw($block_lib_dir)),
            ]);
        }
    }
}
