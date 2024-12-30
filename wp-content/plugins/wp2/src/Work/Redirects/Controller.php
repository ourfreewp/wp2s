<?php

namespace WP2\Work\Admin;

/**
 * Handles admin redirects based on custom settings.
 */
class Redirects
{
    public function __construct()
    {
        add_action('admin_init', [$this, 'setup_redirects']);
    }

    /**
     * Set up admin redirects from stored options.
     */
    public function setup_redirects()
    {
        $redirects = get_option('newsplicity_wordpress_adminRedirects', '[]');
        $redirects = json_decode($redirects, true);

        if (!empty($redirects) && is_array($redirects)) {
            foreach ($redirects as $redirect) {
                if (isset($redirect['from']) && isset($redirect['to'])) {
                    add_action('admin_init', function () use ($redirect) {
                        if (strpos($_SERVER['REQUEST_URI'], $redirect['from']) !== false) {
                            wp_redirect($redirect['to']);
                            exit;
                        }
                    });
                }
            }
        }
    }
}