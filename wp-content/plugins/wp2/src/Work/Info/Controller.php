<?php

namespace WP2\Work\Info;

class Controller
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_get_started_page']);
        add_action('admin_init', [$this, 'set_admin_redirects']);
    }

    /**
     * Set admin redirects based on saved options
     */
    public function set_admin_redirects()
    {
        $redirects = get_option('newsplicity_wordpress_adminRedirects', '[]');
        $redirects = json_decode($redirects, true);

        if (!empty($redirects) && is_array($redirects)) {
            foreach ($redirects as $redirect) {
                if (!empty($redirect['from']) && !empty($redirect['to'])) {
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

    /**
     * Register "Get Started" admin page
     */
    public function register_get_started_page()
    {
        add_menu_page(
            'Get Started',                      // Page title
            'Get Started',                      // Menu title
            'read',                             // Capability
            'wp2docs-get-started',              // Menu slug
            [$this, 'render_get_started_page'], // Callback
            'dashicons-welcome-learn-more',     // Icon
            -1                                  // Menu position
        );
    }

    /**
     * Render "Get Started" admin page content
     */
    public function render_get_started_page()
    {
        ?>
        <style>
            .wp2docs-coda-embed {
                width: 100%;
                height: 100%;
            }
            #wpcontent {
                padding-left: 0;
            }
            #adminmenu {
                margin-top: 0;
            }
            #wpbody-content {
                padding-bottom: 0;
                height: 100vh;
                overflow-x: hidden;
            }
            #wpfooter {
                display: none;
            }
            @media screen and (max-width: 782px) {
                .auto-fold #wpcontent {
                    padding-left: 0;
                }
                #wpbody-content {
                    padding-bottom: 0;
                }
            }
        </style>
        <iframe class="wp2docs-coda-embed" 
                src="https://coda.io/embed/Bxi2WCkyIx/_suEs7?viewMode=embedplay&hideSections=true"
                width="900" height="500" 
                style="max-width: 100%;" allow="fullscreen">
        </iframe>
        <?php
    }
}

// Initialize the controller
new AdminController();