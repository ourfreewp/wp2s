<?php

namespace WP2\Style\Styles;

/**
 * Base Controller to handle general style operations.
 */
class Controller
{

    public function __construct()
    {
        add_filter('body_class', [$this, 'new_body_classes'], 10, 1);
        add_filter('admin_body_class', [$this, 'new_admin_body_classes'], 10, 1);
    }

    
    /**
     * Add admin body class for context-specific styling.
     *
     * @return string
     */
    public function new_admin_body_classes()
    {
        return 'wp-context-admin';
    }

    /**
     * Add body classes for frontend.
     *
     * @param array $classes Existing body classes.
     * @return array Modified body classes.
     */
    public function new_body_classes($classes)
    {
        if (!is_admin()) {
            $classes[] = 'wp-context-front';
        }
        return $classes;
    }
}