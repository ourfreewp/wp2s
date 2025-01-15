<?php
// Path: wp-content/plugins/wp2s/Singles/Programs/init.php
namespace WPS2\Singles\Programs;

class Controller {

    private $textdomain = 'wp2s';
    private $type       = 'wp2s_program';
    private $slug       = 'program';
    private $archive    = 'join';
    private $singular   = 'Program';
    private $plural     = 'Programs';
    private $menu       = 'Programs';
    private $icon       = 'dashicons-money-alt';
    private $archive_query      = [
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ];

    public function __construct() {
        $this->set_archive_query();
    }

    public function extend_post_type() {
        add_filter( 'register_post_type_args', [ $this, 'modify_post_type' ], 10, 2 );
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
                $query->set('post_status', 'publish');
            }
        });
    }

    public function modify_post_type( $args, $post_type ) {
        if ( $this->type === $post_type ) {
            $args['public'] = true;
            $args['publicly_queryable'] = true;
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
}

$controller = new Controller();
$controller->extend_post_type();