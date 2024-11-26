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
    final public static function instance($args = null): self
    {
        if (self::$instance === null) {

            self::$instance = new self($args);
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

    public static function __callStatic(string $method, $args)
    {
        if (!str_starts_with($method, 'with') || !method_exists(self::instance(), $method = mb_substr($method, 4))) {
            throw new \Exception("Method [$method] does not exist on " . __CLASS__);
        }


        switch (count($args)) {
            case 0:
                return self::instance()->$method();

            case 1:
                return self::instance()->$method($args[0]);

            case 2:
                return self::instance()->$method($args[0], $args[1]);

            case 3:
                return self::instance()->$method($args[0], $args[1], $args[2]);

            case 4:
                return self::instance()->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array([self::instance(), $method], $args);
        }
    }
}
