<?php
// Path: wp-content/plugins/wp2/Singles/Controller.php

namespace WP2\Singles;

defined('ABSPATH') or exit;



class Controller
{

    public function __construct()
    {
        add_action( 'init', array( $this, 'init' ) );
        new Block\Controller();
    }

    public function init()
    {
        do_action( 'qm/debug', 'Singles Controller' );
    }

}





