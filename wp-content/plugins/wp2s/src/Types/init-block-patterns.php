<?php
// Path: wp-content/plugins/wp2s/src/Types/BlocksPatterns/init.php

namespace WP2S\Types\BlocksPatterns;

class Controller {

    private $textdomain = 'wp2s';
    private $prefix     = 'wp2s_';
    private $pattern_matchers = [
        'wp2s',
    ];

    public function __construct() {
        add_filter('should_load_remote_block_patterns', '__return_false');
        add_action('after_setup_theme', [$this, 'disable_core_block_patterns']);
        add_filter('rest_dispatch_request', [$this, 'restrict_block_patterns'], 12, 3);
    }

    public function disable_core_block_patterns() {
        remove_theme_support('core-block-patterns');
    }

    public function restrict_block_patterns($dispatch_result, $request, $route) {
        if (preg_match('#^/wp/v2/block-patterns/patterns#', $route)) {
            $patterns = \WP_Block_Patterns_Registry::get_instance()->get_all_registered();
            
            if (!empty($patterns)) {
                foreach ($patterns as $pattern) {
                    if (array_filter($this->pattern_matchers, fn($match) => strpos($pattern['name'], $match) !== false)) {
                        continue;
                    }
                    unregister_block_pattern($pattern['name']);
                }
            }
        }
        return $dispatch_result;
    }
}

$controller = new Controller();