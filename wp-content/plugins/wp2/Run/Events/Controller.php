<?php
// Path: wp-content/plugins/freewp-webhooks/src/events/init-model.php

namespace FreeWP\Core\Models;

use FreeWP\Core\Model;

class Event extends Model
{
    
    /**
     * Constructor.
     */
    public function __construct()
    {
        // do_action( 'qm/debug', 'Event Model Construct' );
    }


    /**
     * Admin Init
     */
    public function admin_init()
    {
        do_action( 'qm/debug', 'Event Model Init' );

        // Load the dummy event directly
        $this->load_dummy_event();
    }
    
    
    /**
     * Get the model name.
     *
     * @return string Model name.
     */
    protected function get_model_name()
    {
        return 'event';
    }

    /**
     * Get the model labels.
     *
     * @return array Model labels.
     */
    protected function get_labels()
    {
        return [
            'name'          => 'Events',
            'singular_name' => 'Event',
        ];
    }

    /**
     * Get the table name.
     *
     * @return string Table name.
     */
    protected function get_table_name()
    {
        global $wpdb;
        return $wpdb->prefix . FREEWP_PREFIX . 'events';
    }

    /**
     * Get the arguments for registering the model.
     *
     * @return array Model registration arguments.
     */
    protected function get_args()
    {
        $labels     = $this->get_labels();
        $textdomain = FREEWP_TEXT_DOMAIN;

        return [
            'table'      => $this->get_table_name(),
            'labels'     => [
                'name'          => _x($labels['name'], 'Model name', $textdomain),
                'singular_name' => _x($labels['singular_name'], 'Model name', $textdomain),
                'menu_name'     => _x($labels['name'], 'Model name', $textdomain),
                'add_new'       => __('Add New', $textdomain),
                'add_new_item'  => sprintf(__('Add New %s', $textdomain), $labels['singular_name']),
                'edit_item'     => sprintf(__('Edit %s', $textdomain), $labels['singular_name']),
                'search_items'  => sprintf(__('Search %s', $textdomain), $labels['name']),
                'not_found'     => sprintf(__('No %s found', $textdomain), $labels['name']),
                'all_items'     => sprintf(__('All %s', $textdomain), $labels['name']),
                'item_updated'  => sprintf(__('%s updated.', $textdomain), $labels['singular_name']),
                'item_added'    => sprintf(__('%s added.', $textdomain), $labels['singular_name']),
                'item_deleted'  => sprintf(__('%s deleted.', $textdomain), $labels['singular_name']),
            ],
            'menu_icon'  => 'dashicons-calendar',
            'parent'     => 'tools.php',
        ];
    }

    /**
     * Get the table columns with data types.
     *
     * @return array Table columns.
     */
    protected function get_table_columns()
    {
        return [
            // Do not add ID column; it's automatically created.
            'event_name'   => 'VARCHAR(255) NOT NULL',
            'source'       => 'VARCHAR(100) NOT NULL',
            'status'       => 'VARCHAR(50) NOT NULL',
            'payload'      => 'LONGTEXT NOT NULL',
            'created_at'   => 'DATETIME NOT NULL',
            'processed_at' => 'DATETIME',
        ];
    }

    /**
     * Get the indexed columns for the table.
     *
     * @return array Indexed columns.
     */
    protected function get_table_indexes()
    {
        return ['event_name', 'source', 'status'];
    }

    /**
     * Get the fields for the meta box.
     *
     * @return array Fields array.
     */
    protected function get_fields()
    {
        return [
            [
                'id'   => 'event_name',
                'name' => 'Event Name',
                'type' => 'text',
                'admin_columns' => true,
            ],
            [
                'id'   => 'source',
                'name' => 'Source',
                'type' => 'text',
                'admin_columns' => true,
            ],
            [
                'id'   => 'status',
                'name' => 'Status',
                'type' => 'select',
                'options' => [
                    'pending'   => 'Pending',
                    'processed' => 'Processed',
                    'failed'    => 'Failed',
                ],
                'admin_columns' => true,
            ],
            [
                'id'   => 'payload',
                'name' => 'Payload',
                'type' => 'textarea',
            ],
            [
                'id'   => 'created_at',
                'name' => 'Created At',
                'type' => 'datetime',
                'js_options'  => [
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => 'HH:mm:ss',
                ],
                'admin_columns' => true,

            ],
            [
                'id'   => 'processed_at',
                'name' => 'Processed At',
                'type' => 'datetime',
                'js_options'  => [
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => 'HH:mm:ss',
                ],
                'admin_columns' => true,
            ],
        ];
    }

    /**
     * Get Plugin Location for Install
     * 
     * @return string
     */
    protected function get_plugin_path()
    {
        return plugin_dir_path(__FILE__);
    }

    public function load_dummy_event()
    {
        
        do_action( 'qm/debug', 'Loading Dummy Event' );
        
        // Ensure the table exists; create it if not
        if (! $this->table_exists()) {
            $this->create_table();
        }

        // Prepare unique dummy event data
        $dummy_event = [
            'event_name'   => 'dummy_event_' . time(), // Make event name unique with timestamp
            'source'       => 'development',
            'status'       => 'pending',
            'payload'      => 'This is a dummy event for logging purposes.',
            'created_at'   => current_time('mysql'),
            'processed_at' => null,
        ];

        // Insert the dummy event into the custom table
        $result = \MB_Custom_Table_API::add(null, $this->get_table_name(), $dummy_event);

        if ( $result === false ) {
            // Log the error if insertion failed
            global $wpdb;
            $error_message = $wpdb->last_error;
            do_action( 'qm/debug', 'Insert failed: ' . $error_message );
        } else {
            // Log the ID of the inserted row
            global $wpdb;
            $id = $wpdb->insert_id;
            do_action( 'qm/debug', 'New dummy event inserted with ID: ' . $id );
        }
    }

    /**
     * Checks if the custom table exists.
     *
     * @return bool True if the table exists, false otherwise.
     */
    protected function table_exists()
    {
        global $wpdb;
        $table_name = $this->get_table_name();

        return $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) === $table_name;
    }

}

// Instantiate the model

$event = new Event();

// Register the model

$event->register_model();