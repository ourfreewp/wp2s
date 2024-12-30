<?php

namespace WP2\One\Rules;

use WP2\One\Rules\SignUp\Controller as SignUpController;
use WP2\One\Rules\SignIn\Controller as SignInController;
use WP2\One\Rules\SignOut\Controller as SignOutController;

/**
 * Main controller for initializing and managing rules.
 */
class Controller
{
    public function __construct()
    {
        new SignUpController();
        new SignInController();
        new SignOutController();
    }
}

// Initialize the rules system
new Controller();