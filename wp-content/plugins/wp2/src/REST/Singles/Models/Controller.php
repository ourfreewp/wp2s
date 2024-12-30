<?php
// Path: wp-content/plugins/wp2/REST/Models/Controller.php
namespace WP2\REST\Singles\Models;

use WP_REST_Controller;
use WP_REST_Server;
use MetaBox\CustomTable\API as MB_Custom_Table_API;

class Controller
{
    protected $namespace = 'freewp/v1';
    protected $rest_base = 'model';

    public function __construct()
    {
        // Register REST routes
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Registers REST API routes
     */
    public function register_routes()
    {
        // Check if data exists and get data
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<table>[\w-]+)/(?P<object_id>\d+)', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [$this, 'get_data'],
                'permission_callback' => [$this, 'permissions_check'],
                'args'                => $this->get_params(),
            ],
        ]);

        // Check if data exists and get data
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<table>[\w-]+)/(?P<object_id>\d+)/exists', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [$this, 'check_exists'],
                'permission_callback' => [$this, 'permissions_check'],
                'args'                => $this->get_params(),
            ],
        ]);

        // Bulk actions and data filtering
        register_rest_route($this->namespace, '/' . $this->rest_base . '/bulk-action/(?P<table>[\w-]+)', [
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => [$this, 'bulk_action'],
            'permission_callback' => [$this, 'permissions_check'],
            'args'                => $this->get_params(['action', 'ids']),
        ]);

        // Add data
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<table>[\w-]+)', [
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => [$this, 'add_data'],
            'permission_callback' => [$this, 'permissions_check'],
            'args'                => $this->get_params(['data']),
        ]);

        // Update data
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<table>[\w-]+)/(?P<object_id>\d+)', [
            'methods'             => WP_REST_Server::EDITABLE,
            'callback'            => [$this, 'update_data'],
            'permission_callback' => [$this, 'permissions_check'],
            'args'                => $this->get_params(['data']),
        ]);

        // Delete data
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<table>[\w-]+)/(?P<object_id>\d+)', [
            'methods'             => WP_REST_Server::DELETABLE,
            'callback'            => [$this, 'delete_data'],
            'permission_callback' => [$this, 'permissions_check'],
            'args'                => $this->get_params(),
        ]);
    }

    /**
     * Check if a custom table has a row for an object.
     */
    public function check_exists($request)
    {
        $object_id = $request['object_id'];
        $table = $request['table'];
        $exists = MB_Custom_Table_API::exists($object_id, $table);

        return rest_ensure_response(['exists' => $exists]);
    }

    /**
     * Retrieve data from the custom table.
     */
    public function get_data($request)
    {
        $object_id = $request['object_id'];
        $table = $request['table'];
        $data = MB_Custom_Table_API::get($object_id, $table);

        if (!$data) {
            return wp_send_json_error(['message' => 'Data not found'], 404);
        }

        return rest_ensure_response($data);
    }

    /**
     * Add data to the custom table.
     */
    public function add_data($request)
    {
        $table = $request['table'];
        $data = $request->get_param('data');

        $result = MB_Custom_Table_API::add(null, $table, $data);

        if ($result === false) {
            global $wpdb;
            return wp_send_json_error(['message' => 'Failed to insert data', 'error' => $wpdb->last_error], 500);
        }

        global $wpdb;
        return rest_ensure_response(['insert_id' => $wpdb->insert_id]);
    }

    /**
     * Update data in the custom table.
     */
    public function update_data($request)
    {
        $object_id = $request['object_id'];
        $table = $request['table'];
        $data = $request->get_param('data');

        $result = MB_Custom_Table_API::update($object_id, $table, $data);

        if (!$result) {
            return wp_send_json_error(['message' => 'Failed to update data'], 500);
        }

        return rest_ensure_response(['updated' => true]);
    }

    /**
     * Delete data from the custom table.
     */
    public function delete_data($request)
    {
        $object_id = $request['object_id'];
        $table = $request['table'];

        $result = MB_Custom_Table_API::delete($object_id, $table);

        if (!$result) {
            return wp_send_json_error(['message' => 'Failed to delete data'], 500);
        }

        return rest_ensure_response(['deleted' => true]);
    }

    /**
     * Perform a bulk action on data in the custom table.
     */
    public function bulk_action($request)
    {
        $table = $request['table'];
        $action = $request->get_param('action');
        $ids = $request->get_param('ids');

        if (empty($action) || !is_array($ids)) {
            return wp_send_json_error(['message' => 'Invalid action or IDs'], 400);
        }

        // Trigger bulk action handler
        do_action("mbct_{$action}_bulk_action", $ids, $table);

        return rest_ensure_response(['message' => 'Bulk action executed', 'action' => $action, 'ids' => $ids]);
    }

    /**
     * Define REST API parameters for each route
     */
    protected function get_params($fields = [])
    {
        $params = [
            'object_id' => [
                'description'       => 'The ID of the object to operate on.',
                'type'              => 'integer',
                'required'          => true,
                'validate_callback' => function ($param) {
                    return is_numeric($param);
                },
            ],
            'table' => [
                'description' => 'The name of the custom table.',
                'type'        => 'string',
                'required'    => true,
            ],
        ];

        if (in_array('data', $fields, true)) {
            $params['data'] = [
                'description' => 'Data to add or update in the custom table.',
                'type'        => 'array',
                'required'    => true,
            ];
        }

        if (in_array('action', $fields, true)) {
            $params['action'] = [
                'description' => 'The bulk action to perform.',
                'type'        => 'string',
                'required'    => true,
            ];
        }

        if (in_array('ids', $fields, true)) {
            $params['ids'] = [
                'description' => 'List of object IDs for bulk action.',
                'type'        => 'array',
                'items'       => ['type' => 'integer'],
                'required'    => true,
            ];
        }

        return $params;
    }

    /**
     * Check permissions for accessing the API.
     */
    public function permissions_check($request)
    {
        return current_user_can('manage_options');
    }
}
