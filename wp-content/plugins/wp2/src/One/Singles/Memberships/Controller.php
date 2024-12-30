<?php
// Path: wp-content/plugins/wp2/One/Singles/Memberships/Controller.php

namespace WP2\One\Singles\Memberships;

class Controller
{
    public function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    public function init() {}
}
