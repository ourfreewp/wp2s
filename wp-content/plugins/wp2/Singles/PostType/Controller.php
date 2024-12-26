<?php
// Path: wp-content/plugins/blockstudio-blocks/blocks/registrar/init-post-types.php
/**
 * Core classes for registering and initializing custom post types.
 *
 * @package FreeWP\Core
 */

namespace FreeWP\Core;

/**
 * Abstract Class PostType
 *
 * Provides a base class for registering custom post types.
 */
abstract class PostType
{

    /**
     * Registers the custom post type.
     */
    public function register_post_type()
    {
        $post_type = $this->get_post_type(); // Retrieve post type slug.
        $args      = $this->get_args();      // Retrieve post type arguments.

        if ( ! post_type_exists( $post_type ) ) {
            register_post_type($post_type, $args); // Register the post type with WordPress.
        }
    }

    /**
     * Get the post type key (slug).
     *
     * @return string Post type slug.
     */
    abstract protected function get_post_type();

    /**
     * Get the arguments for registering the post type.
     *
     * @return array Post type registration arguments.
     */
    abstract protected function get_args();
}