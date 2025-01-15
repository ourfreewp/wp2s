<?php
// Path: wp-content/plugins/wp2s/Types/Topics/init.php

namespace WP2S\Types\Topics;

class Controller {

    private $textdomain = 'wp2s';
    private $prefix     = 'wp2s_';
    private $taxonomy   = 'wp2s_topic';
    private $singular   = 'Topic';
    private $plural     = 'Topics';
    private $slug       = 'topic';
    private $post_types = [
        'wp2s_charter',
    ];
    
    public function __construct() {
        add_action( 'init', [ $this, 'initialize' ], 20 );
    }

    public function initialize() {
        add_action( 'wp_loaded', [ $this, 'register_taxonomy' ] );
    }

    public function register_taxonomy() {
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
            'menu_name'         => __( $this->plural, $this->textdomain ),
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => $this->slug ],
            'show_in_rest'      => true,
        ];

        if (!empty($this->post_types)) {
            register_taxonomy($this->taxonomy, $this->post_types, $args);
        }
    }

}

$controller = new Controller();