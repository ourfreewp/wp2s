<?php
/**
 * WP2 Media Initialization
 *
 * Initializes and bootstraps thumbnail settings and view controllers.
 *
 * @package WP2\Media
 */

namespace WP2\Media;

use WP2\Media\Thumbnails\Settings\Controller as SettingsController;
use WP2\Media\Thumbnails\Views\Controller as ViewsController;

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

class WP2_Media_Init
{
    /**
     * Constructor to register hooks and initialize components
     */
    public function __construct()
    {
        // Register the init hook
        add_action('init', [$this, 'initialize_media_components']);
    }

    /**
     * Initialize Thumbnail Settings and Views
     */
    public function initialize_media_components()
    {
        // Initialize Thumbnail Meta Boxes and Syncing
        new SettingsController();
        
        // Initialize Thumbnail View Enhancements (e.g., Bylines)
        new ViewsController();
    }
}

new WP2_Media_Init();