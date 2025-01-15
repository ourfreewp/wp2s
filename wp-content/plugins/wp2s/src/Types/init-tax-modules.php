<?php
// Path: wp-content/plugins/wp2s/Types/init-tax-modules.php

namespace WP2S\Types\ModuleTaxonomy;

class Controller {

    private $textdomain = 'wp2s';
    private $prefix     = 'wp2s_';
    private $taxonomy   = 'wp2s_tax_module';
    private $singular   = 'Module';
    private $plural     = 'Modules';
    private $slug       = 'wp2';
    private $menu       = 'Modules';
    private $post_types = [];
    
    public function __construct() {
        add_action( 'init', [ $this, 'initialize' ], 20 );
    }

    public function initialize() {
        add_action( 'wp_loaded', [ $this, 'register_taxonomy' ] );
    }

    public function register_taxonomy() {
        $this->get_post_types();
        $this->create_taxonomy();
    }

    public function create_taxonomy() {
        $labels = [
            'name'              => __( $this->plural, $this->textdomain ),
            'singular_name'     => __( $this->singular, $this->textdomain ),
            'search_items'      => __( 'Search ' . $this->plural, $this->textdomain ),
            'all_items'         => __( 'All ' . $this->plural, $this->textdomain ),
            'parent_item'       => __( 'Parent ' . $this->singular, $this->textdomain ),
            'parent_item_colon' => __( 'Parent ' . $this->singular . ':', $this->textdomain ),
            'edit_item'         => __( 'Edit ' . $this->singular, $this->textdomain ),
            'update_item'       => __( 'Update ' . $this->singular, $this->textdomain ),
            'add_new_item'      => __( 'Add New ' . $this->singular, $this->textdomain ),
            'new_item_name'     => __( 'New ' . $this->singular . ' Name', $this->textdomain ),
            'menu_name'         => __( $this->menu, $this->textdomain ),
        ];

        $args = [
            'public'            => true,
            'publicly_queryable'=> true,
            'labels'            => $labels,
            'hierarchical'      => false,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 
                'slug' => $this->slug,
                'with_front' => false,
                'hierarchical' => false,
            ],
            'show_in_rest'      => true,
        ];

        if (!empty($this->post_types)) {
            register_taxonomy($this->taxonomy, $this->post_types, $args);
        }
    }

    // get all post types that start with prefix
    public function get_post_types() {
        $post_types = get_post_types(['_builtin' => false], 'objects');
        
        // get all post types that start with prefix
        foreach ($post_types as $post_type) {
            if (strpos($post_type->name, $this->prefix) === 0) {
                $this->post_types[] = $post_type->name;
            }
        }
    }

}

$controller = new Controller();