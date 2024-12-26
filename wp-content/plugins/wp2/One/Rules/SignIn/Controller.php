<?php

namespace WP2\One\Rules\SignIn;

/**
 * Controller for sign-in rules and customization.
 */
class Controller
{
    public function __construct()
    {
        add_filter('wp_login', [$this, 'track_signin'], 10, 2);
    }

    public function track_signin($user_login, $user)
    {
        error_log("User {$user_login} signed in.");
    }
}