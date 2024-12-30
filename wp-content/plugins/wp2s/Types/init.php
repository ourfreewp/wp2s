<?php

namespace WP2S\Types;

use WP_REST_Request;
use WP_Error;


class Controller
{
    private $textdomain = 'wp2s';
    private $prefix = 'wp2s_';
    private $namespace = 'wp2/v1';

    private $tables = [];

    public function __construct()
    {
        $this->tables = $this->defined_types();

        add_action('init', [$this, 'register_post_types'], 50);
        add_filter('rwmb_meta_boxes', [$this, 'register_meta_boxes'], 50);
        add_action('rest_api_init', [$this, 'register_routes'], 50);
    }

    public function register_post_types()
    {
        if (empty($this->tables)) {
            do_action('qm/debug', 'No tables to register');
            return;
        }

        foreach ($this->tables as $table) {
            if (!isset($table['singular'], $table['plural'], $table['single'], $table['archive'], $table['rest'])) {
                do_action('qm/debug', 'Missing table definition for ' . print_r($table, true));
                continue;
            }

            $labels = $this->generate_labels($table);
            $args   = $this->generate_args($labels, $table);
            $post_type = $this->prefix . strtolower($table['single']);

            register_post_type($post_type, $args);
        }
    }

    private function generate_labels($table)
    {
        return [
            'name'                     => __($table['plural'], $this->textdomain),
            'singular_name'            => __($table['singular'], $this->textdomain),
            'add_new'                  => __('Add New', $this->textdomain),
            'add_new_item'             => __("Add New {$table['singular']}", $this->textdomain),
            'edit_item'                => __("Edit {$table['singular']}", $this->textdomain),
            'new_item'                 => __("New {$table['singular']}", $this->textdomain),
            'view_item'                => __("View {$table['singular']}", $this->textdomain),
            'view_items'               => __("View {$table['plural']}", $this->textdomain),
            'search_items'             => __("Search {$table['plural']}", $this->textdomain),
            'not_found'                => __("No {$table['archive']} found.", $this->textdomain),
            'not_found_in_trash'       => __("No {$table['archive']} found in Trash.", $this->textdomain),
            'parent_item_colon'        => __("Parent {$table['singular']}:", $this->textdomain),
            'all_items'                => __("All {$table['plural']}", $this->textdomain),
            'archives'                 => __("{$table['singular']} Archives", $this->textdomain),
            'attributes'               => __("{$table['singular']} Attributes", $this->textdomain),
            'insert_into_item'         => __("Insert into {$table['singular']}", $this->textdomain),
            'uploaded_to_this_item'    => __("Uploaded to this {$table['singular']}", $this->textdomain),
            'menu_name'                => __($table['plural'], $this->textdomain),
            'filter_items_list'        => __("Filter {$table['archive']} list", $this->textdomain),
            'items_list_navigation'    => __("{$table['plural']} list navigation", $this->textdomain),
            'items_list'               => __("{$table['plural']} list", $this->textdomain),
            'item_published'           => __("{$table['singular']} published.", $this->textdomain),
            'item_updated'             => __("{$table['singular']} updated.", $this->textdomain),
        ];
    }

    private function generate_args($labels, $table)
    {
        return [
            'label'               => __($table['plural'], $this->textdomain),
            'labels'              => $labels,
            'public'              => true,
            'hierarchical'        => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'show_in_rest'        => true,
            'show_in_menu'        => false,
            'rest_base'           => $table['rest'],
            'rest_namespace'      => $this->namespace,
            'query_var'           => true,
            'can_export'          => true,
            'delete_with_user'    => false,
            'has_archive'         => $table['archive'],
            'rewrite'             => [
                'slug'       => strtolower($table['single']),
                'with_front' => false,
            ],
            'menu_icon'           => $table['icon'] ?? 'dashicons-admin-generic',
            'capability_type'     => 'post',
            'supports'            => [
                'title',
                'editor',
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
        // Grab the list of custom post types from your defined_types() method
        $defined_types = $this->defined_types();

        // For each custom post type, define its meta box
        foreach ($defined_types as $type_data) {
            // Build the actual post type slug, e.g. "wp2s_zone"
            $post_type_slug = $this->prefix . strtolower($type_data['single']);

            $meta_boxes[] = [
                'title'      => sprintf(__('WP2S: %s Fields', $this->textdomain), $type_data['singular']),
                'id'         => $this->prefix . 'fields_' . $post_type_slug,
                'post_types' => [$post_type_slug],
                'fields'     => $this->build_fields_for($post_type_slug),
            ];
        }

        return $meta_boxes;
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

    public function defined_types()
    {
        $types =  [
            [
                'singular' => 'Pattern',
                'plural'   => 'Patterns',
                'archive'  => 'patterns',
                'rest'     => 'patterns',
                'single'   => 'pattern',
            ],
            [
                'singular' => 'Command',
                'plural'   => 'Commands',
                'archive'  => 'commands',
                'rest'     => 'commands',
                'single'   => 'command',
            ],
            [
                'singular' => 'Variable',
                'plural'   => 'Variables',
                'archive'  => 'variables',
                'rest'     => 'variables',
                'single'   => 'variable',
            ],
            [
                'singular' => 'Program',
                'plural'   => 'Programs',
                'archive'  => 'programs',
                'rest'     => 'programs',
                'single'   => 'program',
            ],
            [
                'singular' => 'Directory',
                'plural'   => 'Directories',
                'archive'  => 'directories',
                'rest'     => 'directories',
                'single'   => 'directory',
            ],
            [
                'singular' => 'Plugin',
                'plural'   => 'Plugins',
                'archive'  => 'plugins',
                'rest'     => 'plugins',
                'single'   => 'plugin',
            ],
            [
                'singular' => 'Extension',
                'plural'   => 'Extensions',
                'archive'  => 'extensions',
                'rest'     => 'extensions',
                'single'   => 'extension',
            ],
            [
                'singular' => 'Integration',
                'plural'   => 'Integrations',
                'archive'  => 'integrations',
                'rest'     => 'integrations',
                'single'   => 'integration',
            ],
            [
                'singular' => 'Alert',
                'plural'   => 'Alerts',
                'archive'  => 'alerts',
                'rest'     => 'alerts',
                'single'   => 'alert',
            ],
            [
                'singular' => 'Component',
                'plural'   => 'Components',
                'archive'  => 'components',
                'rest'     => 'components',
                'single'   => 'component',
            ],
            [
                'singular' => 'Gist',
                'plural'   => 'Gists',
                'archive'  => 'gists',
                'rest'     => 'gists',
                'single'   => 'gist',
            ],
            [
                'singular' => 'Update',
                'plural'   => 'Updates',
                'archive'  => 'updates',
                'rest'     => 'updates',
                'single'   => 'update',
            ],
            [
                'singular' => 'Product',
                'plural'   => 'Products',
                'archive'  => 'products',
                'rest'     => 'products',
                'single'   => 'product',
            ],
            [
                'singular' => 'Review',
                'plural'   => 'Reviews',
                'archive'  => 'reviews',
                'rest'     => 'reviews',
                'single'   => 'review',
            ],
            [
                'singular' => 'Announcement',
                'plural'   => 'Announcements',
                'archive'  => 'announcements',
                'rest'     => 'announcements',
                'single'   => 'announcement',
            ],
            [
                'singular' => 'Campaign',
                'plural'   => 'Campaigns',
                'archive'  => 'campaigns',
                'rest'     => 'campaigns',
                'single'   => 'campaign',
            ],
            [
                'singular' => 'Report',
                'plural'   => 'Reports',
                'archive'  => 'reports',
                'rest'     => 'reports',
                'single'   => 'report',
            ],
            [
                'singular' => 'Event',
                'plural'   => 'Events',
                'archive'  => 'events',
                'rest'     => 'events',
                'single'   => 'event',
            ],
            [
                'singular' => 'Upload',
                'plural'   => 'Uploads',
                'archive'  => 'uploads',
                'rest'     => 'uploads',
                'single'   => 'upload',
            ],
            [
                'singular' => 'Property',
                'plural'   => 'Properties',
                'archive'  => 'properties',
                'rest'     => 'properties',
                'single'   => 'property',
            ],
            [
                'singular' => 'Asset',
                'plural'   => 'Assets',
                'archive'  => 'assets',
                'rest'     => 'assets',
                'single'   => 'asset',
            ],
            [
                'singular' => 'Studio',
                'plural'   => 'Studios',
                'archive'  => 'studios',
                'rest'     => 'studios',
                'single'   => 'studio',
            ],
            [
                'singular' => 'Offer',
                'plural'   => 'Offers',
                'archive'  => 'offers',
                'rest'     => 'offers',
                'single'   => 'offer',
            ],
            [
                'singular' => 'Form',
                'plural'   => 'Forms',
                'archive'  => 'forms',
                'rest'     => 'forms',
                'single'   => 'form',
            ],
            [
                'singular' => 'Setting',
                'plural'   => 'Settings',
                'archive'  => 'settings',
                'rest'     => 'settings',
                'single'   => 'setting',
            ],
            [
                'singular' => 'Brand',
                'plural'   => 'Brands',
                'archive'  => 'brands',
                'rest'     => 'brands',
                'single'   => 'brand',
            ],
            [
                'singular' => 'Archive',
                'plural'   => 'Archives',
                'archive'  => 'archives',
                'rest'     => 'archives',
                'single'   => 'archive',
            ],
            [
                'singular' => 'Folder',
                'plural'   => 'Folders',
                'archive'  => 'folders',
                'rest'     => 'folders',
                'single'   => 'folder',
            ],
            [
                'singular' => 'Doc',
                'plural'   => 'Docs',
                'archive'  => 'docs',
                'rest'     => 'docs',
                'single'   => 'doc',
            ],
            [
                'singular' => 'Vault',
                'plural'   => 'Vaults',
                'archive'  => 'vaults',
                'rest'     => 'vaults',
                'single'   => 'vault',
            ],
            [
                'singular' => 'Conversation',
                'plural'   => 'Conversations',
                'archive'  => 'conversations',
                'rest'     => 'conversations',
                'single'   => 'conversation',
            ],
            [
                'singular' => 'Collection',
                'plural'   => 'Collections',
                'archive'  => 'collections',
                'rest'     => 'collections',
                'single'   => 'collection',
            ],
            [
                'singular' => 'Endpoint',
                'plural'   => 'Endpoints',
                'archive'  => 'endpoints',
                'rest'     => 'endpoints',
                'single'   => 'endpoint',
            ],
            [
                'singular' => 'Enum',
                'plural'   => 'Enums',
                'archive'  => 'enums',
                'rest'     => 'enums',
                'single'   => 'enum',
            ],
            [
                'singular' => 'Role',
                'plural'   => 'Roles',
                'archive'  => 'roles',
                'rest'     => 'roles',
                'single'   => 'role',
            ],
            [
                'singular' => 'Role',
                'plural'   => 'Roles',
                'archive'  => 'roles',
                'rest'     => 'roles',
                'single'   => 'role',
            ],
            [
                'singular' => 'Role',
                'plural'   => 'Roles',
                'archive'  => 'roles',
                'rest'     => 'roles',
                'single'   => 'role',
            ],
            [
                'singular' => 'Badge',
                'plural'   => 'Badges',
                'archive'  => 'badges',
                'rest'     => 'badges',
                'single'   => 'badge',
            ],
            [
                'singular' => 'Page',
                'plural'   => 'Pages',
                'archive'  => 'pages',
                'rest'     => 'pages',
                'single'   => 'page',
            ],
            [
                'singular' => 'Plugin',
                'plural'   => 'Plugins',
                'archive'  => 'plugins',
                'rest'     => 'plugins',
                'single'   => 'plugin',
            ],
            [
                'singular' => 'Work',
                'plural'   => 'Work',
                'archive'  => 'work',
                'rest'     => 'work',
                'single'   => 'work',
            ],
            [
                'singular' => 'Post',
                'plural'   => 'Posts',
                'archive'  => 'posts',
                'rest'     => 'posts',
                'single'   => 'post',
            ],
            [
                'singular' => 'Question',
                'plural'   => 'Questions',
                'archive'  => 'questions',
                'rest'     => 'questions',
                'single'   => 'question',
            ],
            [
                'singular' => 'Space',
                'plural'   => 'Spaces',
                'archive'  => 'spaces',
                'rest'     => 'spaces',
                'single'   => 'space',
            ],
            [
                'singular' => 'Team',
                'plural'   => 'Teams',
                'archive'  => 'teams',
                'rest'     => 'teams',
                'single'   => 'team',
            ],
            [
                'singular' => 'Theme',
                'plural'   => 'Themes',
                'archive'  => 'themes',
                'rest'     => 'themes',
                'single'   => 'theme',
            ],
            [
                'singular' => 'Zone',
                'plural'   => 'Zones',
                'archive'  => 'zones',
                'rest'     => 'zones',
                'single'   => 'zone',
            ],
            [
                'singular' => 'Part',
                'plural'   => 'Parts',
                'archive'  => 'parts',
                'rest'     => 'parts',
                'single'   => 'part',
            ],
            [
                'singular' => 'News',
                'plural'   => 'News',
                'archive'  => 'news',
                'rest'     => 'news',
                'single'   => 'news',
            ],
            [
                'singular' => 'Group',
                'plural'   => 'Groups',
                'archive'  => 'groups',
                'rest'     => 'groups',
                'single'   => 'group',
            ],
            [
                'singular' => 'Check',
                'plural'   => 'Checks',
                'archive'  => 'checks',
                'rest'     => 'checks',
                'single'   => 'check',
            ],
            [
                'singular' => 'Term',
                'plural'   => 'Terms',
                'archive'  => 'terms',
                'rest'     => 'terms',
                'single'   => 'term',
            ],
            [
                'singular' => 'Industry',
                'plural'   => 'Industries',
                'archive'  => 'industries',
                'rest'     => 'industries',
                'single'   => 'industry',
            ],
            [
                'singular' => 'Fund',
                'plural'   => 'Funds',
                'archive'  => 'funds',
                'rest'     => 'funds',
                'single'   => 'fund',
            ],
            [
                'singular' => 'Statement',
                'plural'   => 'Statements',
                'archive'  => 'statements',
                'rest'     => 'statements',
                'single'   => 'statement',
            ],
            [
                'singular' => 'Block',
                'plural'   => 'Blocks',
                'archive'  => 'blocks',
                'rest'     => 'blocks',
                'single'   => 'block',
            ],
            [
                'singular' => 'Link',
                'plural'   => 'Links',
                'archive'  => 'links',
                'rest'     => 'links',
                'single'   => 'link',
            ],
            [
                'singular' => 'Wiki',
                'plural'   => 'Wikis',
                'archive'  => 'wikis',
                'rest'     => 'wikis',
                'single'   => 'wiki',
            ],
            [
                'singular' => 'Tool',
                'plural'   => 'Tools',
                'archive'  => 'tools',
                'rest'     => 'tools',
                'single'   => 'tool',
            ],
            [
                'singular' => 'Partner',
                'plural'   => 'Partners',
                'archive'  => 'partners',
                'rest'     => 'partners',
                'single'   => 'partner',
            ],
            [
                'singular' => 'Guide',
                'plural'   => 'Guides',
                'archive'  => 'guides',
                'rest'     => 'guides',
                'single'   => 'guide',
            ],
            [
                'singular' => 'Maker',
                'plural'   => 'Makers',
                'archive'  => 'makers',
                'rest'     => 'makers',
                'single'   => 'maker',
            ],
            [
                'singular' => 'Model',
                'plural'   => 'Models',
                'archive'  => 'models',
                'rest'     => 'models',
                'single'   => 'model',
            ],
            [
                'singular' => 'Profile',
                'plural'   => 'Profiles',
                'archive'  => 'profiles',
                'rest'     => 'profiles',
                'single'   => 'profile',
            ],
        ];
        return $types;
    }
}

new Controller();
