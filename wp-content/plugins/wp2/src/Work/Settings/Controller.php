<?php

namespace WP2\Work\Settings;

/**
 * Handles admin menu additions and custom pages.
 */
class Controller
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_get_started_page']);
    }

    /**
     * Add 'Get Started' admin menu item.
     */
    public function add_get_started_page()
    {
        add_menu_page(
            'Get Started',                            // Page title
            'Get Started',                            // Menu title
            'read',                                   // Capability
            'wp2docs-get-started',                    // Menu slug
            [$this, 'render_get_started_content'],    // Callback function to display content
            'dashicons-welcome-learn-more',           // Menu icon
            -1                                        // Menu position
        );
    }

    /**
     * Render content for the 'Get Started' page.
     */
    public function render_get_started_content()
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
                width="900" 
                height="500" 
                style="max-width: 100%;" 
                allow="fullscreen">
        </iframe>
        <?php
    }
}