<?php
namespace WP2S\Singles\Modules\Collections;

class Controller {

    private $taxonomy   = 'wp2s_collection';
    private $post_type  = 'wp2s_module';

    public function __construct() {
        add_action( 'init', [ $this, 'init' ] , 41 );
    }

    public function init() {
        $this->attach_taxonomy();
        do_action( 'qm/debug', 'Collections: Attached to Modules' );
    }

    public function attach_taxonomy() {
        add_action( 'init', [ $this, 'register_taxonomy_to_post_type' ] );
    }

    public function register_taxonomy_to_post_type() {
        register_taxonomy_for_object_type( $this->taxonomy, $this->post_type );
    }
}

$controller = new Controller();