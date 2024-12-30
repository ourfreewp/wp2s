<?php

namespace MustUse\Cloud\Auth;

class SignIn
{

    public function context($data)
    {

        $context = '';

        if (is_user_logged_in()) {
            if (current_user_can('manage_options')) {
                $context = 'logged-in-admin';
            } else {
                $context = 'logged-in';
            }
        } else {
            // logged out users are coming from the logout referrer
            if (isset($_GET['logged-out'])) {
                $context = 'logged-out-visitor';
            } else {
                $context = 'logged-out-visitor';
            }
        }

        return $context;
    }

    public function view($context)
    {

        $view = '';

        switch ($context) {
            case 'logged-in':
                $view = 'logged-in';
                break;
            case 'logged-in-admin':
                $view = 'logged-in-admin';
                break;
            case 'logged-out-user':
                $view = 'logged-out-visitor';
                break;
            case 'logged-out-visitor';
                $view = 'logged-out-visitor';
                break;
            default:
                $view = 'logged-out-visitor';
                break;
        }

        return   $view;
    }
}
