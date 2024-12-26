<?php
// Path: wp-content/mu-plugins/examplepress/init-fields.php
/**
 * Core classes for registering and initializing custom metaboxes and fields.
 *
 * @package ExamplePress\Core
 */

namespace ExamplePress\Core;

/**
 * Abstract Class MetaBox
 *
 * Provides a base class for registering custom metaboxes.
 */
abstract class FieldGroup
{
    /**
     * Registers the metabox.
     */
    public function register_meta_box()
    {
        add_filter('rwmb_meta_boxes', [$this, 'add_meta_boxes']);
    }

    /**
     * Adds metaboxes to the Meta Box plugin.
     *
     * @param array $meta_boxes Existing metaboxes.
     * @return array Modified metaboxes with the new metabox added.
     */
    public function add_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = $this->get_meta_box(); // Retrieve metabox configuration.

        return $meta_boxes;
    }

    /**
     * Get the metabox configuration.
     *
     * @return array Metabox configuration array.
     */
    abstract protected function get_meta_box();
}
