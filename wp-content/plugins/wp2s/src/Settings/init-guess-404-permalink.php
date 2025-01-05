<?php
// Path: wp-content/plugins/wp2s/src/Settings/Guess404Permalink/init.php
namespace WP2\Settings\Guess404Permalink;

class Controller
{
    public function __construct()
    {
        add_filter('do_redirect_guess_404_permalink', '__return_false');
    }
}

new Controller();