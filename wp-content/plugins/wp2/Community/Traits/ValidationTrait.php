<?php

WP2\Community\Traits;

/**
 * Provides validation utilities.
 */
trait ValidationTrait
{
    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
