<?php

/**
 * Controller.php
 *
 * Logs messages to PHP error_log.
 *
 * @package WP2\Dev\Helpers\Logging\ErrorLog
 */

namespace WP2\Dev\Helpers\Logging\ErrorLog;

class Controller
{
    /**
     * Log a message to the server log (error_log).
     *
     * @param string $message The message to log.
     * @param string $level   Log level (e.g., 'info', 'error').
     */
    public static function log($message, $level = 'info')
    {
        $level_prefix = strtoupper($level);
        error_log("[$level_prefix] $message");
    }
}