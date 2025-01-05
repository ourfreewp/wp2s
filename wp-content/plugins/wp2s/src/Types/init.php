<?php

namespace WP2S\Types;

use WP_REST_Request;
use WP_Error;


class Controller
{
    private $textdomain = 'wp2s';
    private $prefix = 'wp2s_';
    private $namespace = 'wp2/v1';
    private $definition_path = WP2S_PLUGIN_DIR . 'src/Types/definitions/';
    private $definitions = [];
    private $definition_cache_key = 'wp2s_definitions_cache';

    public function __construct()
    {

        $this->definitions = $this->get_definitions();

        add_action('init', [$this, 'register_post_types'], 50);
        add_filter('rwmb_meta_boxes', [$this, 'register_meta_boxes'], 50);
        add_action('rest_api_init', [$this, 'register_routes'], 50);

        // add_action('init', [$this, 'debug_definitions'], 50);

    }

    public function load_definitions()
    {
        
        $cached_definitions = get_transient($this->definition_cache_key);
    
        if ($cached_definitions === false) {
            // Cache miss – regenerate definitions
            $definitions = $this->get_definitions();
    
            // Store in transient for 1 hour
            set_transient($cache_key, $definitions, HOUR_IN_SECONDS);
    
            do_action('qm/debug', 'Regenerated definitions and set transient.');
        } else {
            // Cache hit
            $definitions = $cached_definitions;
            do_action('qm/debug', 'Loaded definitions from cache.');
        }
    
        $this->definitions = $definitions;

    }

    public function register_post_types()
    {
        if (empty($this->definitions)) {
            do_action('qm/debug', 'No tables to register');
            return;
        }

        foreach ($this->definitions as $definition) {
            if (!isset($definition['singular'], $definition['plural'], $definition['single'], $definition['archive'], $definition['rest'])) {
                do_action('qm/debug', 'Missing definition for ' . print_r($definition, true));
                continue;
            }

            $labels = $this->generate_labels($definition);
            $args   = $this->generate_args($labels, $definition);
            $post_type = $this->prefix . strtolower($definition['type'] ?? $definition['single']);

            register_post_type($post_type, $args);
        }
    }

    private function generate_labels($definition)
    {
        return [
            'name'                     => __($definition['plural'], $this->textdomain),
            'singular_name'            => __($definition['singular'], $this->textdomain),
            'add_new'                  => __('Add New', $this->textdomain),
            'add_new_item'             => __("Add New {$definition['singular']}", $this->textdomain),
            'edit_item'                => __("Edit {$definition['singular']}", $this->textdomain),
            'new_item'                 => __("New {$definition['singular']}", $this->textdomain),
            'view_item'                => __("View {$definition['singular']}", $this->textdomain),
            'view_items'               => __("View {$definition['plural']}", $this->textdomain),
            'search_items'             => __("Search {$definition['plural']}", $this->textdomain),
            'not_found'                => __("No {$definition['archive']} found.", $this->textdomain),
            'not_found_in_trash'       => __("No {$definition['archive']} found in Trash.", $this->textdomain),
            'parent_item_colon'        => __("Parent {$definition['singular']}:", $this->textdomain),
            'all_items'                => __("All {$definition['plural']}", $this->textdomain),
            'archives'                 => __("{$definition['singular']} Archives", $this->textdomain),
            'attributes'               => __("{$definition['singular']} Attributes", $this->textdomain),
            'insert_into_item'         => __("Insert into {$definition['singular']}", $this->textdomain),
            'uploaded_to_this_item'    => __("Uploaded to this {$definition['singular']}", $this->textdomain),
            'menu_name'                => __($definition['plural'], $this->textdomain),
            'filter_items_list'        => __("Filter {$definition['archive']} list", $this->textdomain),
            'items_list_navigation'    => __("{$definition['plural']} list navigation", $this->textdomain),
            'items_list'               => __("{$definition['plural']} list", $this->textdomain),
            'item_published'           => __("{$definition['singular']} published.", $this->textdomain),
            'item_updated'             => __("{$definition['singular']} updated.", $this->textdomain),
        ];
    }

    private function generate_args($labels, $definition)
    {
        return [
            'label'               => __($definition['plural'], $this->textdomain),
            'labels'              => $labels,
            'public'              => true,
            'hierarchical'        => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'show_in_rest'        => true,
            'show_in_menu'        => false,
            'rest_base'           => $definition['rest'],
            'rest_namespace'      => $this->namespace,
            'query_var'           => true,
            'can_export'          => true,
            'delete_with_user'    => false,
            'has_archive'         => false,
            'rewrite'             => [
                'slug'       => strtolower($definition['single']),
                'with_front' => false,
            ],
            'menu_icon'           => $definition['icon'] ?? 'dashicons-admin-generic',
            'capability_type'     => 'post',
            'supports'            => [
                'title',
                'thumbnail',
                'excerpt',
                'author',
                'custom-fields',
                'comments',
                'revisions',
                'page-attributes'
            ],
            'taxonomies'          => [],
        ];
    }

    public function register_meta_boxes($meta_boxes)
    {
        $defined_types = $this->defined_type_slugs();
        $added_types = $this->added_definition_slugs();
    
        $all_types = array_merge($defined_types, $added_types);
    
        foreach ($all_types as $slug => $type_data) {
            $meta_boxes[] = [
                'title'      => sprintf(__('WP2S: %s Fields', $this->textdomain), $type_data['singular']),
                'id'         => $this->prefix . 'fields_' . $slug,
                'post_types' => [$slug],
                'fields'     => $this->build_fields_for($slug),
            ];
        }
    
        return $meta_boxes;
    }

    protected function defined_type_slugs()
    {
        $defined_types = $this->get_definitions();

        $slugs = [];
    
        foreach ($defined_types as $type_data) {
            // Generate the slug with prefix
            $slug = $this->prefix . strtolower($type_data['single']);
            $slugs[$slug] = $type_data;
        }
    
        return $slugs;
    }

    protected function added_definition_slugs()
    {
        return [
            'page' => [
                'singular' => 'Page',
                'single'   => 'page'
            ],
        ];
    }

    public function register_routes()
    {
        register_rest_route($this->namespace, '/autocomplete/fields', [
            'methods'             => 'GET',
            'callback'            => [$this, 'get_post_type_fields'],
            'permission_callback' => [$this, 'check_permissions'],
            'args'                => [
                'post_type' => [
                    'required'          => true,
                    'validate_callback' => [$this, 'validate_post_type'],
                ],
            ],
        ]);
    }

    public function get_post_type_fields(WP_REST_Request $request)
    {
        $post_type = $request->get_param('post_type');

        $resolved_post_type = $this->lookup_post_type_by_rest_base($post_type);

        if (is_wp_error($resolved_post_type)) {
            error_log("Post type not found for rest_base: $post_type");
            return rest_ensure_response([]);  // Return empty array on failure (optional)
        }

        // Fetch fields for the resolved post type
        $fields = rwmb_get_object_fields($resolved_post_type);

        if (empty($fields)) {
            return new WP_Error('no_fields', __('No fields found for this post type', $this->textdomain), ['status' => 404]);
        }

        $field_names = array_map(function ($field) {
            return $field['id'];
        }, $fields);

        return rest_ensure_response($field_names);
    }

    /**
     * Get all definition json files and merge them into a single array. definition path with type.json name format.
     * defintions are merged in with defined types.
     * @return array
     */

    public function get_definitions()
    {
        $definitions = [];

        $dir = $this->definition_path;

       // bring all definitions into an array and return the array
        $files = glob($dir . '*.json');

        foreach ($files as $file) {
            $json = file_get_contents($file);
            $data = json_decode($json, true);
            // push the data into the definitions array don't merge
            $definitions[] = $data;
        }


        return $definitions;
    }

    /**
     * Lookup post type by matching the REST base path.
     */
    public function lookup_post_type_by_rest_base($rest_path)
    {
        $post_types = get_post_types([], 'objects');

        foreach ($post_types as $post_type => $object) {
            $rest_base = isset($object->rest_base) ? $object->rest_base : $post_type;
            if ($rest_base === $rest_path) {
                return $post_type;
            }
        }

        return new WP_Error(
            'invalid_post_type',
            __('Invalid post type REST path.', $this->textdomain),
            ['status' => 400]
        );
    }

    /**
     * Validate post type during REST argument processing.
     */
    public function validate_post_type($post_type, $request, $param)
    {
        $resolved_post_type = $this->lookup_post_type_by_rest_base($post_type);

        if (is_wp_error($resolved_post_type)) {
            return false;
        }

        return true;
    }

    /**
     * Allow access to all authenticated users.
     */
    public function check_permissions(WP_REST_Request $request)
    {
        return true;  // Permit access for now
    }

    /**
     * Helper to get site by domain for multisite.
     */
    private function get_site_by_domain($domain)
    {
        $sites = get_sites([
            'domain' => $domain,
            'number' => 1
        ]);

        return !empty($sites) ? $sites[0] : false;
    }

    private function build_fields_for($post_type)
    {

        $fields_data = [
            'id'          => 'Id',
            'identifier'  => 'Identifier',
            'name'        => 'Name',
            'description' => 'Description',
            'url'         => 'URL',
            'home'        => 'Home',
            'doc'         => 'Doc',
            'space'       => 'Space',
            'zone'        => 'Zone',
            'wiki'        => 'Wiki',
            'domain'      => 'Domain',
            'folder'      => 'Folder',
            'collection'  => 'Collection',
            'topic'       => 'Topic',
            'brand'       => 'Brand',
            'category'    => 'Category',
            'status'      => 'Status',
            'area'        => 'Area',
            'network'     => 'Network',
            'provider'    => 'Provider',
            'type'        => 'Type',
            'is_featured' => 'Featured',
        ];

        $extra_fields_map = [
            'wp2s_plugin'  => [
                'wp_plugin_id' => 'WP Id',
                'wp_plugin_name' => 'WP Name',
                'wp_plugin_author' => 'WP Author',
                'wp_plugin_description' => 'WP Description',
                'wp_plugin_textdomain' => 'WP Textdomain',
                'wp_plugin_version' => 'WP Version',
                'wp_plugin_url' => 'WP URL',
                'wp_plugin_home_url' => 'WP Home URL',
                'wp2_type' => 'Type',
                'wp2_name' => 'Name',
                'wp2_description' => 'Description',
                'wp2_text_domain' => 'Text Domain',
                'wp2_version' => 'Version',
                'wp2_author' => 'Author',
                'wp2_license' => 'License',
                'wp2_network' => 'Network',
                'wp2_requires_at_least' => 'Requires At Least',
                'wp2_tested_up_to' => 'Tested Up To',
                'wp2_requires_php' => 'Requires PHP',
                'wp2_requires_plugins' => 'Requires Plugins',
                'wp2_author_uri' => 'Author URI',
                'wp2_download_uri' => 'Download URI',
                'wp2_license_uri' => 'License URI',
                'wp2_update_uri' => 'Update URI',
                'wp2_home_uri' => 'Home URI',
                'wp2_cover_photo_uri' => 'Cover Photo',
                'wp2_profile_photo_uri' => 'WP Profile Photo',
                'wp2_thumbnail_uri' => 'Thumbnail',
                'wp2_banner_uri' => 'Banner',
                'wp2_logo_uri' => 'Logo',
                'wp2_icon_uri' => 'Icon',
                'wp2_screenshot_uri' => 'Screenshot',
                'wp2_screenshots' => 'Screenshots',
            ],
        ];

        // If the current post type has extra fields, merge them
        if (isset($extra_fields_map[$post_type])) {
            $fields_data = array_merge($fields_data, $extra_fields_map[$post_type]);
        }

        // Common attributes
        $common_attributes = [
            'type'              => 'text',
            'required'          => false,
            'disabled'          => true,
            'readonly'          => true,
            'clone'             => false,
            'clone_empty_start' => false,
            'hide_from_rest'    => false,
            'hide_from_front'   => false,
        ];

        return array_map(function ($key, $label) use ($common_attributes) {
            $field = array_merge(
                [
                    'name' => __($label, $this->textdomain),
                    'id'   => $this->prefix . $key,
                ],
                $common_attributes
            );

            if ($key === 'description') {
                $field['type'] = 'textarea';
            } elseif ($key === 'is_featured') {
                $field['type'] = 'checkbox';
            }

            return $field;
        }, array_keys($fields_data), $fields_data);
    }
}

new Controller();
