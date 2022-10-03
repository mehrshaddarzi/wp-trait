<?php

namespace WPTrait\Utils;

trait Singleton
{
    /** @var self|null */
    private static $instance = null;

    /**
     * @return static
     * @psalm-return self
     */
    final public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        // Intentionally empty
    }

    private function __clone()
    {
        // Intentionally empty
    }
}
