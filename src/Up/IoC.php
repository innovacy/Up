<?php
/**
 * Created by PhpStorm.
 * User: Dimitrios
 * Date: 20.08.2016
 * Time: 20:46
 */

namespace Innovacy\Up;

/**
 * An extremely simple (high-performance) Inversion of Control class
 * @package Innovacy\Up
 */
class IoC
{
    /** @var array Object registry */
    protected static $registry = array();

    /**
     * Registers an object with a key
     * @param string $key
     * @param object $object
     * @return void
     */
    public static function register($key, $object)
    {
        self::$registry[$key] = $object;
    }

    /**
     * Returns the requested object
     * @param string $key
     * @return object
     */
    public static function get($key)
    {
        if (array_key_exists($key, self::$registry)) {
            return self::$registry[$key];
        }
        return null;
    }
}