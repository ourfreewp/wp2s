<?php
// Path: wp-content/plugins/extend-ws-form/src/BlockPatterns/init.php

namespace WP2\Extend\WSForm\BlockPatterns;

class Controller
{
    /**
     * List of block patterns to unregister.
     *
     * @var array
     */
    private $patterns = [
        'ws-form/signup-1',
        'ws-form/signup-2',
    ];

    /**
     * Controller constructor.
     * Hooks the unregistration process to 'wp_loaded'.
     */
    public function __construct()
    {
        add_action('wp_loaded', [$this, 'unregister_block_patterns']);


    }

    /**
     * Unregister specified block patterns.
     */
    public function unregister_block_patterns()
    {
        // Check if the unregister_block_pattern function exists to avoid errors.
        if (function_exists('unregister_block_pattern')) {
            foreach ($this->patterns as $pattern) {
                unregister_block_pattern($pattern);
                // Optional: Log the unregistration for debugging.
                error_log("Block pattern {$pattern} unregistered.");
            }
        } else {
            // Optional: Log if the function does not exist (older WP versions).
            error_log("unregister_block_pattern function not found.");
        }
    }
}

// Instantiate the Controller to trigger the functionality.
new Controller();