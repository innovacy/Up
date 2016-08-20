<?php
/**
 * Created by PhpStorm.
 * User: Dimitrios
 * Date: 20.08.2016
 * Time: 23:01
 */

namespace Innovacy\Up;

/**
 * Class Configuration
 * @package Innovacy\Up
 */
class Configuration
{
    /**
     * @var array Values registry
     */
    protected $registry = array();

    /**
     * Returns true if a configuration key has a value, otherwise false
     * @param string $key
     * @return bool
     */
    public function hasValue($key)
    {
        return array_key_exists($this->registry, $key);
    }

    /**
     * Returns the value of a configuration key or an empty array if no value is set
     * (false could be a valid key value, an empty array should be not)
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (array_key_exists($this->registry, $key)) {
            return $this->registry[$key];
        }
        return array();
    }

    /**
     * Loads the configuration from a json file
     * @param string $configFile
     * @return void
     */
    public function loadConfiguration($configFile)
    {
        $this->setValues(json_decode(file_get_contents($configFile), true));
    }

    /**
     * Sets values with an map array
     * @param $map
     * @return void
     */
    public function setValues($map)
    {
        $this->registry = array_merge($this->registry, $map);
    }
}
