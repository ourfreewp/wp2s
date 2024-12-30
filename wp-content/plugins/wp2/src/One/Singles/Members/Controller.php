<?php
// Path: wp-content/plugins/wp2/One/Singles/Members/Controller.php

namespace WP2\One\Singles\Members;

class Controller
{
    public function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    public function init() {}
}
