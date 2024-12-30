<?php

namespace WP2\Style\Templates\PostTypes;

/**
 * Controller to manage post type templates in WP2 Style.
 */
class Controller
{
    public function __construct()
    {
        add_action('wp_loaded', [$this, 'remove_plugin_templates']);
    }

    /**
     * Hook into post type templates and remove unnecessary ones.
     */
    public function remove_plugin_templates()
    {
        $post_types = get_post_types([], 'names');

        foreach ($post_types as $post_type) {
            add_filter("theme_{$post_type}_templates", [$this, 'filter_templates']);
        }
    }

    /**
     * Filter out specified templates from post types.
     *
     * @param array $templates List of available templates.
     * @return array Modified template list.
     */
    public function filter_templates($templates)
    {
        $templates_to_remove = [
            'page-templates/full-width.php',
            'page-templates/canvas.php',
            'page-templates/canvas-scroll.php'
        ];

        foreach ($templates_to_remove as $template) {
            if (isset($templates[$template])) {
                unset($templates[$template]);
            }
        }

        return $templates;
    }
}