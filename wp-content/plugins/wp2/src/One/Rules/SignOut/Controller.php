<?php

namespace WP2\One\Rules\SignOut;

/**
 * Controller for sign-out rules and customization.
 */
class Controller
{
    public function __construct()
    {
        add_action('clear_auth_cookie', [$this, 'track_signout']);
    }

    public function track_signout()
    {
        error_log('User signed out.');
    }
}