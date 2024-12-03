<?php
// Path: wp-content/plugins/wp2-rest/src/REST/Network/Sites/Controller.php

namespace WP2\REST\Network\Sites;

use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Response;
use WP_Error;

class Controller extends WP_REST_Controller
{

    public function __construct()
    {
        $this->namespace = WP2_REST_NAMESPACE . '/v1';
        $this->rest_base = 'sites';

        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Register the routes for this controller.
     */
    public function register_routes()
    {
        register_rest_route($this->namespace, '/sites', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [$this, 'handle_get'],
        ]);
    }

    /**
     * Handle the GET request to retrieve the list of sites.
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response|WP_Error
     */
    public function handle_get($request)
    {
        // Check if the user has permission to view the sites
        if (!current_user_can('manage_sites')) {
            return new WP_Error('insufficient_permissions', 'You do not have permission to view sites', ['status' => 403]);
        }

        // Retrieve all sites in the network
        $sites = get_sites();

        if (empty($sites)) {
            return new WP_Error('no_sites', 'No sites found', ['status' => 404]);
        }

        // Return the list of sites as a REST response
        return new WP_REST_Response($sites);
    }
}
