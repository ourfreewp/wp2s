<?php

namespace WP2\One\Rules\SignUp;

use WP2\One\Rules\SignUp\AllowedDomains\Controller as AllowedDomainsController;

/**
 * Controller for managing the sign-up process and rules.
 */
class Controller
{
    private $allowed_domains_rule;

    public function __construct()
    {
        $this->allowed_domains_rule = new AllowedDomainsController();
        $this->allowed_domains_rule->set_allowed_domains(['vinnysgreen.com', 'example.com']);
    }
}