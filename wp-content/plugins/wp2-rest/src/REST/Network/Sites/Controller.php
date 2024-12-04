<?php
// Path: wp-content/plugins/wp2-rest/src/REST/Network/Sites/Controller.php

namespace WP2\REST\Network\Sites;

use WP_REST_Server;
use WP_REST_Response;
use WP_Error;
use WP_Site_Query;

class Controller
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Register the routes for this controller.
     */
    public function register_routes()
    {
        // Register the '/sites' route
        register_rest_route('wp2/v1', '/sites', [
            'methods'  => WP_REST_Server::READABLE,  // Define it as a GET request
            'permission_callback' => [$this, 'permissions_check'],
            'callback' => [$this, 'handle_get'],     // The callback function for this endpoint
        ]);
    }

    /**
     * Check if the current user has permission to access this endpoint.
     *
     * @return bool
     */
    public function permissions_check()
    {
        // Check if the current user can manage network options
        return current_user_can('manage_options');
    }

    /**
     * Handle the GET request to retrieve the list of sites.
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response|WP_Error
     */
    public function handle_get($request)
    {
        $number = $request->get_param('per_page') ? $request->get_param('per_page') : 100;

        $offset = $request->get_param('page') ? ($request->get_param('page') - 1) * 100 : 0;

        $args = [
            'number'                 => $number,
            'offset'                 => $offset,
            'no_found_rows'          => true,
            'orderby'                => 'id',
            'order'                  => 'ASC',
            'public'                 => null,
            'archived'               => null,
            'mature'                 => null,
            'spam'                   => null,
            'deleted'                => null,
            'lang_id'                => null,
            'count'                  => false,
            'date_query'             => null,
            'update_site_cache'      => true,
            'update_site_meta_cache' => true,
        ];

        // The Site Query
        $sites = new WP_Site_Query($args);

        if (empty($sites)) {
            return new WP_Error('no_sites', 'No sites found', ['status' => 404]);
        }

        // Return the list of sites as a REST response
        return new WP_REST_Response($sites);
    }
}
