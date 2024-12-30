<?php
// Path: wp-content/plugins/blockstudio-blocks/blocks/registrar/init-taxonomies.php
/**
 * Core classes for registering and initializing custom taxonomies.
 *
 * @package FreeWP\Core
 */

namespace FreeWP\Core;

/**
 * Abstract Class Taxonomy
 *
 * Provides a base class for registering custom taxonomies.
 */
abstract class Taxonomy
{

    /**
     * Registers the custom taxonomy.
     */
    public function register_taxonomy()
    {
        $taxonomy    = $this->get_taxonomy();    // Retrieve taxonomy slug.
        $args        = $this->get_args();        // Retrieve taxonomy arguments.
        $object_type = $this->get_object_type(); // Retrieve the object types.

        if (!taxonomy_exists($taxonomy)) {
            register_taxonomy($taxonomy, $object_type, $args); // Register the taxonomy with WordPress.
        }
    }

    /**
     * Get the taxonomy key (slug).
     *
     * @return string Taxonomy slug.
     */
    abstract protected function get_taxonomy();

    /**
     * Get the object types the taxonomy applies to.
     *
     * @return array Object types for the taxonomy.
     */
    abstract protected function get_object_type();

    /**
     * Get the arguments for registering the taxonomy.
     *
     * @return array Taxonomy registration arguments.
     */
    abstract protected function get_args();

    public function term_meta_boxes($meta_boxes)
    {
        $text_domain = 'examplepress';
    
        $taxonomies = get_taxonomies();
    
        $meta_boxes[] = [
            'title'      => __('Taxonomy Details', $text_domain),
            'id'         => 'taxonomy-details',
            'taxonomies' => $taxonomies,
            'fields'     => [
                [
                    'name' => __('Featured Image', $text_domain),
                    'id'   => 'featured_image',
                    'type' => 'single_image',
                ],
                [
                    'name' => __('Alternate Name', $text_domain),
                    'id'   => 'alternate_name',
                    'type' => 'text',
                ],
                [
                    'name' => __('Slogan', $text_domain),
                    'id'   => 'slogan',
                    'type' => 'text',
                ],
                [
                    'name' => __('Sticky', $text_domain),
                    'id'   => 'sticky',
                    'type' => 'number',
                ],
                [
                    'name' => __('Position', $text_domain),
                    'id'   => 'position',
                    'type' => 'number',
                ]
            ],
        ];
    
        return $meta_boxes;
    }
    
    public function term_meta()
    {
    
        // Featured Image
        register_meta(
            'term',
            'featured_image',
            array(
                'type'         => 'integer',
                'description'  => 'Featured Image',
                'single'       => true,
                'show_in_rest' => true,
            )
        );
    
        // Alternate Name
        register_meta(
            'term',
            'alternate_name',
            array(
                'type'         => 'string',
                'description'  => 'Alternate Name',
                'single'       => true,
                'show_in_rest' => true,
            )
        );
    
        // Slogan
        register_meta(
            'term',
            'slogan',
            array(
                'type'         => 'string',
                'description'  => 'Slogan',
                'single'       => true,
                'show_in_rest' => true,
            )
        );
    
        // Sticky
        register_meta(
            'term',
            'sticky',
            array(
                'type'         => 'integer',
                'description'  => 'Sticky',
                'single'       => true,
                'show_in_rest' => true,
            )
        );
    
        // Position
        register_meta(
            'term',
            'position',
            array(
                'type'         => 'integer',
                'description'  => 'Position',
                'single'       => true,
                'show_in_rest' => true,
            )
        );
    }
}    