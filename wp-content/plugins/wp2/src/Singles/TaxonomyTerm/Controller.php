<?php

/**
 * TaxonomyManager Class
 *
 * This class handles the registration and management of custom taxonomies for studios.
 * Specifically, it creates an `enumerations` taxonomy, registers global and namespaced
 * taxonomies, and assigns them to the relevant post types.
 *
 * Responsibilities:
 * - Register the core `enumerations` taxonomy and its terms.
 * - Dynamically register taxonomies based on enumeration terms.
 * - Handle both global and namespaced taxonomies.
 *
 * @package MustUseStudios
 */

namespace MustUse\Studios\Helpers;

class TaxonomyManager
{
    /**
     * Constructor
     * Initializes the TaxonomyManager and hooks into WordPress actions.
     */
    public function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    /**
     * Initialize the TaxonomyManager.
     */
    public function init()
    {
        // Register the core enumerations taxonomy
        $this->register_enumerations_taxonomy();

        // Register global and namespaced taxonomies
        $this->register_taxonomies();
    }

    /**
     * Register the core `enumerations` taxonomy for studios.
     */
    private function register_enumerations_taxonomy()
    {
        $args = [
            'labels' => $this->generate_taxonomy_labels('Enumerations', 'Enumeration'),
            'description' => 'A collection of named values that can be used to define various categories within studios.',
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'rest_base' => MU_PREFIX . 'enumerations',
            'rewrite' => [
                'slug' => MU_PREFIX . 'enumerations',
                'with_front' => true,
            ],
            'capabilities' => $this->get_taxonomy_capabilities(),
        ];

        // Register the `enumerations` taxonomy only for the main studios post type
        register_taxonomy(MU_PREFIX . 'enumerations', [MU_PREFIX . 'studio'], $args);

        // Register the reserved terms under this taxonomy
        $this->register_reserved_terms();
    }

    /**
     * Register the reserved enumeration terms under the `enumerations` taxonomy.
     */
    private function register_reserved_terms()
    {
        $terms = $this->get_reserved_enumeration_terms();

        foreach ($terms as $term) {
            if (!term_exists($term, MU_PREFIX . 'enumerations')) {
                wp_insert_term($term, MU_PREFIX . 'enumerations');
            }
        }
    }

    /**
     * Register global and namespaced taxonomies based on the enumeration terms.
     */
    private function register_taxonomies()
    {
        $terms = $this->get_reserved_enumeration_terms();

        foreach ($terms as $term => $details) {
            if ($details['global']) {
                // Register global taxonomy
                $this->register_global_taxonomy($term);
            } else {
                // Register namespaced taxonomy for each studio post type
                $this->register_namespaced_taxonomy($term);
            }
        }
    }

    /**
     * Register a global taxonomy for a given term.
     *
     * @param string $term The enumeration term (e.g., 'Data Types').
     */
    private function register_global_taxonomy($term)
    {
        $args = [
            'labels' => $this->generate_taxonomy_labels(ucfirst($term), ucfirst($term)),
            'description' => "A global collection of {$term} used within studios.",
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'rest_base' => MU_PREFIX . strtolower(str_replace(' ', '_', $term)),
            'rewrite' => [
                'slug' => MU_PREFIX . strtolower(str_replace(' ', '_', $term)),
                'with_front' => true,
            ],
            'capabilities' => $this->get_taxonomy_capabilities(),
        ];

        register_taxonomy(MU_PREFIX . strtolower(str_replace(' ', '_', $term)), [MU_PREFIX . 'studio'], $args);
    }

    /**
     * Register a namespaced taxonomy for a given term, applicable to all studio post types.
     *
     * @param string $term The enumeration term (e.g., 'Attributes').
     */
    private function register_namespaced_taxonomy($term)
    {
        $studio_post_types = $this->get_studio_post_types();

        foreach ($studio_post_types as $post_type) {
            $taxonomy_name = substr(MU_PREFIX . $post_type . '_' . strtolower(str_replace(' ', '_', $term)), 0, 32);

            $args = [
                'labels' => $this->generate_taxonomy_labels(ucfirst($term), ucfirst($term)),
                'description' => "A namespaced collection of {$term} for {$post_type}.",
                'public' => true,
                'hierarchical' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'show_in_rest' => true,
                'rest_base' => $taxonomy_name,
                'rewrite' => [
                    'slug' => $taxonomy_name,
                    'with_front' => true,
                ],
                'capabilities' => $this->get_taxonomy_capabilities(),
            ];

            register_taxonomy($taxonomy_name, [$post_type], $args);
        }
    }

    /**
     * Generate taxonomy labels for a given term.
     *
     * @param string $plural The plural name of the taxonomy.
     * @param string $singular The singular name of the taxonomy.
     * @return array The generated labels.
     */
    private function generate_taxonomy_labels($plural, $singular)
    {
        return [
            'name' => $plural,
            'singular_name' => $singular,
            'search_items' => 'Search ' . $plural,
            'all_items' => 'All ' . $plural,
            'edit_item' => 'Edit ' . $singular,
            'view_item' => 'View ' . $singular,
            'update_item' => 'Update ' . $singular,
            'add_new_item' => 'Add New ' . $singular,
            'new_item_name' => 'New ' . $singular . ' Name',
            'menu_name' => $plural,
            'parent_item' => 'Parent ' . $singular,
            'parent_item_colon' => 'Parent ' . $singular . ':',
        ];
    }

    /**
     * Get the list of reserved enumeration terms.
     *
     * @return array The list of terms with their properties (global or namespaced).
     */
    private function get_reserved_enumeration_terms()
    {
        return [
            'Data Types' => ['global' => true],
            'SQL Types' => ['global' => true],
            'Meta Box Types' => ['global' => true],
            'Attributes' => ['global' => false],
            'Keywords' => ['global' => false],
            'Types' => ['global' => false],
        ];
    }

    /**
     * Get the list of post types associated with the studio taxonomies.
     *
     * @return array The list of post type slugs.
     */
    private function get_studio_post_types()
    {
        return ['mu_studio', 'mu_api', 'mu_dataset', 'mu_type', 'mu_block', 'mu_brand', 'mu_experience', 'mu_extension', 'mu_thing'];
    }

    /**
     * Get taxonomy capabilities for managing taxonomies.
     *
     * @return array The capabilities array.
     */
    private function get_taxonomy_capabilities()
    {
        return [
            'manage_terms' => 'manage_options',
            'edit_terms' => 'manage_options',
            'delete_terms' => 'manage_options',
            'assign_terms' => 'edit_posts',
        ];
    }
}