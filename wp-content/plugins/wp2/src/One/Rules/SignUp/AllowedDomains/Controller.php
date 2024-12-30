<?php

namespace WP2\One\Rules\SignUp\AllowedDomains;

use WP_Error;

/**
 * Controller for allowed domain sign-up restrictions.
 */
class Controller
{
    private $allowed_domains = ['vinnysgreen.com'];

    public function __construct()
    {
        add_filter('nsl_registration_user_data', [$this, 'apply_rule'], 10, 3);
    }

    public function apply_rule($user_data, $provider, $errors)
    {
        if (isset($user_data['email']) && is_email($user_data['email'])) {
            $email_parts = explode('@', $user_data['email']);
            $domain = $email_parts[1];

            if (!in_array($domain, $this->allowed_domains)) {
                $errors->add('invalid_email', __('ERROR: Registration from this email domain is not allowed!', 'wp2'));
            }
        } else {
            $errors->add('invalid_email', __('ERROR: Invalid or missing email.', 'wp2'));
        }

        return $user_data;
    }

    public function set_allowed_domains(array $domains)
    {
        $this->allowed_domains = $domains;
    }

    public function get_allowed_domains()
    {
        return $this->allowed_domains;
    }
}