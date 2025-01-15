<?php
namespace WPS2\Singles\Plugins;

class Controller {

    private $textdomain    = 'wp2s';
    private $type          = 'wp2s_plugin';
    private $archive       = 'plugins';
    private $slug          = 'plugin';
    private $singular      = 'Plugin';
    private $plural        = 'Plugins';
    private $menu          = 'Plugins';
    private $icon          = 'dashicons-plugins-checked';
    private $archive_query = [
        'posts_per_page' => -1,
        'orderby'        => 'post_title',
        'order'          => 'ASC',
        'post_status'    => ['plugin_awaiting_docs','publish'],
        'post_parent'    => 0,
    ];

    public function __construct() {
        $this->set_archive_query();
    }

    public function extend_post_type() {
        add_filter( 'register_post_type_args', [ $this, 'modify_post_type' ], 10, 2 );
        add_action('init', [$this, 'add_statuses'], 99);
    }

    public function modify_post_type( $args, $post_type ) {
        if ( $this->type === $post_type ) {
            $args['publicly_queryable'] = true;
            $args['public'] = true;
            $args['show_ui'] = true;
            $args['show_in_menu'] = true;
            if ( ! in_array( 'editor', $args['supports'] ) ) {
                $args['supports'][] = 'editor';
            }
            $args['has_archive'] = $this->archive;
            $args['rewrite'] = [
                'slug' => $this->slug,
                'with_front' => false,
            ];
            $args['menu_icon'] = $this->icon;
            $args['labels'] = [
                'name'               => $this->plural,
                'singular_name'      => $this->singular,
                'menu_name'          => $this->menu,
                'name_admin_bar'     => $this->singular,
                'add_new'            => 'Add New',
                'add_new_item'       => 'Add New ' . $this->singular,
                'new_item'           => 'New ' . $this->singular,
                'edit_item'          => 'Edit ' . $this->singular,
                'view_item'          => 'View ' . $this->singular,
                'all_items'          => 'All ' . $this->plural,
                'search_items'       => 'Search ' . $this->plural,
                'parent_item_colon'  => 'Parent ' . $this->plural . ':',
                'not_found'          => 'No ' . strtolower( $this->plural ) . ' found.',
                'not_found_in_trash' => 'No ' . strtolower( $this->plural ) . ' found in Trash.',
            ];
        }
        return $args;
    }

    public function set_archive_query()
    {
        add_action('pre_get_posts', function ($query) {

            $archive_query = $this->archive_query;

            if (
                !is_admin() && 
                $query->is_main_query() && 
                $query->is_post_type_archive($this->type)
            ) {
                $query->set('posts_per_page', $archive_query['posts_per_page']);
                $query->set('orderby', $archive_query['orderby']);
                $query->set('order', $archive_query['order']);
                $query->set('post_status', $archive_query['post_status']);
                $query->set('post_parent', $archive_query['post_parent']);
            }
        });
    }

    public function add_statuses() {
        register_post_status( 'plugin_awaiting_docs', [
            'label'                     => 'Awaiting Docs',
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Awaiting Docs <span class="count">(%s)</span>', 'Awaiting Docs <span class="count">(%s)</span>' ),
        ] );
    }

}

$controller = new Controller();
$controller->extend_post_type();