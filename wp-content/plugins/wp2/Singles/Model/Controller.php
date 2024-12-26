<?php
// Path: wp-content/mu-plugins/freewp/init-models.php
/**
 * Core classes for registering and initializing custom models.
 *
 * @package FreeWP\Core
 */


namespace FreeWP\Core;

use MetaBox\CustomTable\API as MB_Custom_Table_API;

/**
 * Abstract Class Model
 *
 * Provides a base class for registering custom models.
 */
abstract class Model
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Register the model after all plugins are loaded.
        add_action('plugins_loaded', [$this, 'register_model']);

        add_action('init', [$this, 'init']);

        // Create the custom table on plugin activation.
        register_activation_hook($this->get_plugin_path(), [$this, 'create_table']);
    }


    /**
     *  Init
     */

    public function init()
    {
        if (! function_exists('rwmb_meta_boxes')) {
            // Meta Box plugin is not available.
            do_action('qm/debug', 'Meta Box plugin is not available.');
        } else {
            add_filter('rwmb_meta_boxes', [$this, 'add_meta_boxes']);
            do_action('qm/debug', 'Meta Box plugin is available.');
        }
    }

    /**
     * Adds meta boxes.
     *
     * @param array $meta_boxes Existing meta boxes.
     * @return array Modified meta boxes.
     */
    public function add_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title'        => $this->get_meta_box_title(),
            'models'       => [$this->get_model_name()],
            'storage_type' => 'custom_table',
            'table'        => $this->get_table_name(),
            'fields'       => $this->get_fields(),
        ];

        return $meta_boxes;
    }


    /**
     * Registers the custom model.
     */
    public function register_model()
    {
        $model_name = $this->get_model_name(); // Retrieve the model name.
        $args       = $this->get_args();       // Retrieve model registration arguments.

        // Check if the Meta Box functions are available.
        if (! function_exists('mb_register_model')) {
            // Meta Box plugin or Custom Table extension is not available.
            do_action('qm/debug', 'Meta Box plugin or Custom Table extension is not available.');
            return;
        }

        mb_register_model($model_name, $args); // Register the custom model.

    }

    /**
     * Creates the custom table for the model.
     */
    public function create_table()
    {
        $table_name = $this->get_table_name();    // Get the table name.
        $columns    = $this->get_table_columns(); // Get the table columns.
        $indexes    = $this->get_table_indexes(); // Get the indexed columns.

        // Check if the Meta Box Custom Table API is available.
        if (! class_exists('MetaBox\CustomTable\API')) {
            // Meta Box Custom Table extension is not available.
            return;
        }
        
        // Create the custom table.
        MB_Custom_Table_API::create(
            $table_name,
            $columns,
            $indexes,
            true // Indicate that this is for a custom model.
        );
    }


    /**
     * Get the meta box title.
     *
     * @return string Meta box title.
     */
    protected function get_meta_box_title()
    {
        return $this->get_labels()['singular_name'] . ' Details';
    }

    /**
     * Get the model name.
     *
     * @return string Model name.
     */
    abstract protected function get_model_name();

    /**
     * Get the model labels.
     *
     * @return array Model labels.
     */
    abstract protected function get_labels();

    /**
     * Get the table name.
     *
     * @return string Table name.
     */
    abstract protected function get_table_name();

    /**
     * Get the arguments for registering the model.
     *
     * @return array Model registration arguments.
     */
    abstract protected function get_args();

    /**
     * Get the table columns with data types.
     *
     * @return array Table columns.
     */
    abstract protected function get_table_columns();

    /**
     * Get the indexed columns for the table.
     *
     * @return array Indexed columns.
     */
    abstract protected function get_table_indexes();

    /**
     * Get the fields for the meta box.
     *
     * @return array Fields array.
     */
    abstract protected function get_fields();

    /**
     * Get the plugin 
     * 
     * @return string Plugin name.
     */
    abstract protected function get_plugin_path();
}
