<?php

namespace Pattern;


class Singleton
{
    private static array $instances = [];

    protected function __construct() { }

    protected function __clone() { }

    /**
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }
    
    public static function getInstance(): static
    {
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static();
        }

        return self::$instances[$subclass];
    }
}
