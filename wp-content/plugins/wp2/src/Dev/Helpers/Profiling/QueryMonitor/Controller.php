<?php

/**
 * Controller.php
 *
 * Provides helper methods for profiling code execution using Query Monitor.
 *
 * @package WP2\Dev\Helpers\Profiling\QueryMonitor
 */

namespace WP2\Dev\Helpers\Profiling\QueryMonitor;

class Controller
{
    /**
     * Start a profiler timer.
     *
     * @param string $name The name of the timer.
     */
    public static function start($name)
    {
        do_action('qm/start', $name);
    }

    /**
     * Stop a profiler timer.
     *
     * @param string $name The name of the timer.
     */
    public static function stop($name)
    {
        do_action('qm/stop', $name);
    }

    /**
     * Record a lap in the profiler timer.
     *
     * @param string $name The name of the timer.
     */
    public static function lap($name)
    {
        do_action('qm/lap', $name);
    }
}