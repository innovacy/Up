<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 07.10.2015
 * Time: 08:46
 */

namespace Innovacy;

use Innovacy\Up\Markdown;
use Innovacy\Up\Navigation;

/**
 * Class Up
 * @package Innovacy
 */
class Up
{
    /**
     * @var MarkDown
     */
    protected $parserNavigation;

    /**
     * @var MarkDown
     */
    protected $parserMain;

    /**
     * @var MarkDown
     */
    protected $parserFooter;
    
    /**
     * This is the main app
     */
    public function run()
    {
        $uri = $this->getVirtualUri();
        $filePath = dirname(__FILE__);

        $checkNeedsUpdate = false;
        $redirectToHtml = false;
        if (is_file($filePath.$uri.'.md')) {
            $file = $filePath . $uri . '.md';
        } elseif (is_file($filePath.str_replace('.html', '', $uri).'.md')) {
            $file = $filePath.str_replace('.html', '', $uri).'.md';
            // todo needs to check if html exists and updateable
# Recognize all supported linking styles from possible markdown documents outside, using mdwiki or not converted by Up!
        } elseif (is_file($filePath.$uri.'.html')) {
            // this should never be reached now
            $file = $filePath . $uri;
            $checkNeedsUpdate = true;
            $redirectToHtml = true;
        } elseif (is_file($filePath.str_replace('.md', '', $uri).'.html')) {
            $file = $filePath . str_replace('.md', '', $uri);
            $checkNeedsUpdate = true;
            $redirectToHtml = true;
        } elseif (is_file($filePath.$uri)) {
            $file = $filePath.$uri;
            // TODO: remove! This (#!) isn't working, it's not possible to parse the fragment at all, so we only can change url's we encounter in documents, but not serve any that is called this way
            // TODO #2: Once a page is created (as example index.html) a javascript might be able to recognize if (index.html#!something.md) were called and sent this to the server recognizable
        } elseif (is_file($filePath.str_replace('#!', '', $uri))) {
            $file = $filePath.str_replace('#!', '', $uri);
        } elseif (is_file($filePath.$uri.'/index.md')) {
            $file = $filePath.$uri.'/index.md';
        } elseif (is_file($filePath.'/404.md')) {
            $file = $filePath.'/404.md';
        } else {
            echo '404 File not found'; // TODO: Improve!
        }

        $this->parserMain = new Markdown();
        $this->parserMain->html5 = true;
        $markdown = file_get_contents($file);
        $markup = $this->parserMain->parse($markdown);

        $this->parserNavigation = new Navigation();
        $navigation = $this->parserNavigation->parse(file_get_contents($filePath.'/navigation.md'));
        $css = '';
        if (is_file($filePath.'/css/innovacy.css')) {
            $css = '<link rel="stylesheet" href="'.'/css/innovacy.css'.'" type="text/css">';
        }

        $footer = '';
        if (is_file($filePath.'/footer.md')) {
            $this->parserFooter = new Markdown();
            $this->parserFooter->html5 = true;
            $footer = $this->parserFooter->parse(file_get_contents($filePath . '/footer.md'));
        }

        return <<<HTML
<!doctype html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="generator" content="Up!" />
	$css
	<style>
		body { font-family: Arial, sans-serif; }
		code { background: #eeeeff; padding: 2px; }
		li { margin-bottom: 5px; }
		img { max-width: 1200px; }
		table, td, th { border: solid 1px #ccc; border-collapse: collapse; }
	</style>
</head>
<body>
$navigation
$markup
$footer
</body>
</html>
HTML;
    }

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
