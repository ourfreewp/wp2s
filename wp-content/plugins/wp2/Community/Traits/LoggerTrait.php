<?php

WP2\Community\Traits;

/**
 * Provides logging functionality.
 */
trait LoggerTrait
{
    public function log(string $message): void
    {
        error_log("[LOG]: $message");
    }
}
