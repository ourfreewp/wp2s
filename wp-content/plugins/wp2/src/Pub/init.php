<?php

namespace WP2\Pub;

class Controller
{
    public function __construct()
    {
        // Initialize hooks during theme and block pattern setup.
        add_action('after_setup_theme', [$this, 'disable_core_block_patterns']);
        add_action('wp_loaded', [$this, 'register_custom_block_patterns']);
        add_filter('should_load_remote_block_patterns', '__return_false');
        add_filter('rest_dispatch_request', [$this, 'restrict_block_patterns'], 12, 3);
        add_filter('block_editor_settings_all', [$this, 'disable_openverse_media_category']);
        add_filter('block_editor_settings_all', [$this, 'restrict_code_editor_for_non_admins']);
        add_filter('wp_check_post_lock_window', [$this, 'set_post_lock_window']);
        add_filter('do_redirect_guess_404_permalink', '__return_false');
        
        // Remove Block Directory Assets
        remove_action('enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets');
    }

    /**
     * Disable core block patterns.
     */
    public function disable_core_block_patterns()
    {
        remove_theme_support('core-block-patterns');
    }

    /**
     * Register custom block pattern categories.
     */
    public function register_custom_block_patterns()
    {
        register_block_pattern_category(
            'main',
            ['label' => __('Main Patterns', 'oddnews')]
        );
    }

    /**
     * Restrict block patterns to exclude those not matching specific criteria.
     *
     * @param mixed $dispatch_result
     * @param \WP_REST_Request $request
     * @param string $route
     * @return mixed
     */
    public function restrict_block_patterns($dispatch_result, $request, $route)
    {
        // Check if the request is targeting block patterns.
        if (preg_match('/^\/wp\/v2\/block-patterns\/patterns$/', $route)) {

            $patterns = \WP_Block_Patterns_Registry::get_instance()->get_all_registered();

            if (!empty($patterns)) {
                foreach ($patterns as $pattern) {
                    // Skip patterns containing 'onthewater' in their name.
                    if (strpos($pattern['name'], 'onthewater') !== false) {
                        continue;
                    }
                    unregister_block_pattern($pattern['name']);
                }

                // Remove core block patterns after unregistering custom ones.
                remove_theme_support('core-block-patterns');
            }
        }

        return $dispatch_result;
    }

    /**
     * Disable Openverse media category in the block editor.
     *
     * @param array $settings The editor settings.
     * @return array Modified settings with Openverse disabled.
     */
    public function disable_openverse_media_category($settings)
    {
        $settings['enableOpenverseMediaCategory'] = false;
        return $settings;
    }

    /**
     * Restrict code editor to administrators.
     *
     * @param array $settings The editor settings.
     * @return array Modified settings with code editing disabled for non-admins.
     */
    public function restrict_code_editor_for_non_admins($settings)
    {
        if (!current_user_can('activate_plugins')) {
            $settings['codeEditingEnabled'] = false;
        }
        return $settings;
    }

    /**
     * Set post lock window to 30 seconds.
     *
     * @param int $limit Default lock window (in seconds).
     * @return int Modified lock window.
     */
    public function set_post_lock_window($limit)
    {
        return 30;
    }
}

// Initialize the class.
new Controller();