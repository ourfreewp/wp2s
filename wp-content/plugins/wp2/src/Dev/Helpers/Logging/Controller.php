<?php

/**
 * Controller.php
 *
 * Main logging controller for WP2 Dev. Delegates to specific logging services.
 *
 * @package WP2\Dev\Helpers\Logging
 */

namespace WP2\Dev\Helpers\Logging;

use WP2\Dev\Helpers\Logging\QueryMonitor\Controller as QMLogger;
use WP2\Dev\Helpers\Logging\ErrorLog\Controller as ErrorLogger;

class Controller
{
    /**
     * Log a message.
     *
     * @param string $message     The message to log.
     * @param string $level       Log level (e.g., 'info', 'error').
     * @param string $destination Log destination ('qm', 'error_log', 'both').
     * @param array  $context     Optional. Additional context data.
     */
    public static function log($message, $level = 'info', $destination = 'qm', array $context = [])
    {
        $interpolated_message = self::interpolate($message, $context);

        if (in_array($destination, ['qm', 'both'], true)) {
            QMLogger::log($interpolated_message, $level);
        }

        if (in_array($destination, ['error_log', 'both'], true)) {
            ErrorLogger::log($interpolated_message, $level);
        }
    }

    /**
     * Interpolate context values into the message placeholders.
     */
    private static function interpolate($message, array $context = [])
    {
        $replace = [];
        foreach ($context as $key => $val) {
            $replace["{{$key}}"] = (string) $val;
        }
        return strtr($message, $replace);
    }
}