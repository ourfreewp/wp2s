<?php
// Path: wp-content/plugins/wp2s/Interfaces/AdminBar/Actions/init.php

namespace WP2S\Interfaces\AdminBar\Actions;

class Controller
{
    private $textdomain = 'wp2s';

    public function __construct()
    {
        add_action('admin_bar_menu', [$this, 'add_wp2s_async_action_parent'], 99);
    }

    // Add Parent Menu to Admin Bar
    public function add_wp2s_async_action_parent($admin_bar)
    {
        $admin_bar->add_node([
            'id'    => 'wp2s_async_action',
            'title' => 'WP2S Actions',
            'href'  => false,
            'meta'  => [
                'title' => __('WP2S Actions', $this->textdomain),
            ],
        ]);
    }
}

// Instantiate the controller to hook into WordPress
new Controller();