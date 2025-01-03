<?php

WP2\Community\Traits;

/**
 * Implements the Singleton design pattern.
 */
trait SingletonTrait
{
    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }
    private function __clone()
    {
    }
    private function __wakeup()
    {
    }
}
