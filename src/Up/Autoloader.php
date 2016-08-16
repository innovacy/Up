<?php
/**
 * $Id$
 *
 * Up! - An extremely simple, yet powerful markdown-based CMS
 *
 * Copyright (c) 2015 Innovacy, Dimitrios Karvounaris
 *
 * @version 2.0.0
 * @copyright 2015 Innovacy - Dimitrios Karvounaris
 * @author Dimitrios Karvounaris, <d.karvounaris@innovacy.com>
 * @url https://www.github.com/Innovacy/Up
 * @license See LICENSE file.
 *
 * --
 *
 * NOTICE OF LICENSE
 *
 * Up! is licensed under the terms of the GNU AGPLv3 with additional terms and
 * linking exceptions. The AGPLv3 license text can be found in the AGPLv3.txt file,
 * the additional terms can be found in the LICENSE file.
 *
 */

namespace Innovacy\Up;

/**
 * A simple and efficient PSR-4 autoloader
 * @package Innovacy\Up
 */
class Psr4Autoloader
{
    /**
     * An associative array where the key is a namespace prefix and the value
     * is the base directory for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = array();

    /**
     * Register loader with SPL autoloader stack.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Adds a base directory for a namespace prefix.
     *
     * @param string $prefix The namespace prefix.
     * @param string $base_dir A base directory for class files in the
     * namespace.
     * @param bool $prepend If true, prepend the base directory to the stack
     * instead of appending it; this causes it to be searched first rather
     * than last.
     * @return void
     */
    public function addNamespace($prefix, $base_dir)
    {
        $prefix = trim($prefix, '\\') . '\\';

        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

        $this->prefixes[$prefix] = $base_dir;
    }

    /**
     * Loads the class file for a given class name.
     *
     * @param string $class The fully-qualified class name.
     * @return mixed The mapped file name on success, or boolean false on
     * failure.
     */
    public function loadClass($class)
    {
        $prefix = $class;

        // work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name
        while (false !== $pos = strrpos($prefix, '\\')) {

            // retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);

            // the rest is the relative class name
            $relative_class = substr($class, $pos + 1);

            // try to load a mapped file for the prefix and relative class
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }

            $prefix = rtrim($prefix, '\\');
        }

        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @param string $prefix The namespace prefix.
     * @param string $relative_class The relative class name.
     * @return mixed Boolean false if no mapped file can be loaded, or the
     * name of the mapped file that was loaded.
     */
    protected function loadMappedFile($prefix, $relative_class)
    {
        if (!isset($this->prefixes[$prefix])) {
            return false;
        }

        $file = $this->prefixes[$prefix] . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require $file;
            return $file;
        }
        return false;
    }
}
