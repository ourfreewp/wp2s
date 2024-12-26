<?php

namespace WP2\Studio\Helpers\Registrar;

use WP2\Studio\Helpers\StudioConfig;
use WP2\Studio\Helpers\TaxonomyManager;

class Controller
{
    /**
     * Constructor to initialize custom post types and taxonomies.
     */
    public function __construct()
    {
        add_action('init', [$this, 'register_studios']);
        add_action('init', [$this, 'register_taxonomies']);
    }

    /**
     * Register custom post types for studios.
     */
    public function register_studios()
    {
        $studios = StudioConfig::get_studios();

        foreach ($studios as $studio => $args) {
            $post_type = 'wp2_' . sanitize_key($studio);

            $labels = $this->generate_labels($args['labels']);
            $rewrite = $this->generate_rewrite_rules($args['rewrite']);

            $default_args = [
                'labels' => $labels,
                'public' => true,
                'has_archive' => $args['has_archive'] ?? true,
                'rewrite' => $rewrite,
                'supports' => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'],
                'menu_position' => $args['menu_position'] ?? 25,
                'show_in_rest' => true,
            ];

            register_post_type($post_type, $default_args);
        }
    }

    /**
     * Register taxonomies using a Taxonomy Manager helper.
     */
    public function register_taxonomies()
    {
        $taxonomy_manager = new TaxonomyManager();
        $taxonomy_manager->register_taxonomies();
    }

    /**
     * Generate labels dynamically for custom post types.
     */
    private function generate_labels($labels)
    {
        $singular = $labels['singular_name'];
        $plural = $labels['name'];

        return [
            'name' => $plural,
            'singular_name' => $singular,
            'add_new' => "Add New $singular",
            'add_new_item' => "Add New $singular",
            'edit_item' => "Edit $singular",
            'new_item' => "New $singular",
            'view_item' => "View $singular",
            'search_items' => "Search $plural",
            'not_found' => "No " . strtolower($plural) . " found",
            'not_found_in_trash' => "No " . strtolower($plural) . " found in trash",
            'all_items' => "All $plural",
            'archives' => "$plural Archives",
            'insert_into_item' => "Insert into " . strtolower($singular),
            'uploaded_to_this_item' => "Uploaded to this " . strtolower($singular),
            'menu_name' => $plural,
        ];
    }

    /**
     * Generate rewrite rules for custom post types.
     */
    private function generate_rewrite_rules($rewrite)
    {
        return [
            'slug' => $rewrite['slug'] ?? sanitize_key($rewrite['name']),
            'with_front' => false,
            'pages' => true,
            'feeds' => false,
        ];
    }
}
