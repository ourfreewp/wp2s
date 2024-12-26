<?php

/**
 * Controller.php
 *
 * Logs messages to Query Monitor.
 *
 * @package WP2\Dev\Helpers\Logging\QueryMonitor
 */

namespace WP2\Dev\Helpers\Logging\QueryMonitor;

class Controller
{
    /**
     * Log a message to Query Monitor.
     *
     * @param string $message The message to log.
     * @param string $level   Log level (e.g., 'info', 'error').
     */
    public static function log($message, $level = 'info')
    {
        $level_action_map = [
            'debug'     => 'qm/debug',
            'info'      => 'qm/info',
            'notice'    => 'qm/notice',
            'warning'   => 'qm/warning',
            'error'     => 'qm/error',
            'critical'  => 'qm/critical',
            'alert'     => 'qm/alert',
            'emergency' => 'qm/emergency',
        ];

        do_action($level_action_map[$level] ?? 'qm/info', $message);
    }
}