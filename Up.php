<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 07.10.2015
 * Time: 08:46
 */

namespace Innovacy;

/**
 * Class Up
 * @package Innovacy
 */
class Up
{
    public function getVirtualUri()
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $requestUri = $_SERVER['REQUEST_URI'];
        // Physical path
        if (strpos($requestUri, $scriptName) !== false) {
            $physicalPath = $scriptName;
        } else {
            $physicalPath = str_replace('\\', '', dirname($scriptName));
        }
        // Virtual path
        $pathInfo = $requestUri;
        if (substr($requestUri, 0, strlen($physicalPath)) == $physicalPath) {
            $pathInfo = substr($requestUri, strlen($physicalPath));
        }
        $pathInfo = parse_url($pathInfo, PHP_URL_PATH);
        $pathInfo = '/' . ltrim($pathInfo, '/');
        return $pathInfo;
    }
}
