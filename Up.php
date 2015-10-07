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
    protected $virtualUri;
    /** @var MarkDown The parser class */
    protected $parserNavigation;

    /** @var MarkDown The parser class */
    protected $parserMain;

    /** @var MarkDown The parser class */
    protected $parserFooter;

    /** @var string Base local path */
    protected $basePath;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->basePath = dirname(__FILE__);
        $this->virtualUri = $this->getVirtualUri();

        $this->parserMain = new Markdown();
        $this->parserMain->html5 = true;

        $this->parserNavigation = new Navigation();
        $this->parserNavigation->html5 = true;

        $this->parserFooter = new Markdown();
        $this->parserFooter->html5 = true;
    }
    
    /**
     * This is the main app
     */
    public function run()
    {

        $checkNeedsUpdate = false;
        $redirectToHtml = false;
        $file = $this->lookupFile();

        $markdown = file_get_contents($file);
        $markup = $this->parserMain->parse($markdown);

        $navigation = $this->parserNavigation->parse(file_get_contents($this->basePath .'/navigation.md'));
        $css = '';
        if (is_file($this->basePath .'/css/innovacy.css')) {
            $css = '<link rel="stylesheet" href="'.'/css/innovacy.css'.'" type="text/css">';
        }

        $footer = '';
        if (is_file($this->basePath .'/footer.md')) {
            $footer = $this->parserFooter->parse(file_get_contents($this->basePath . '/footer.md'));
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

    /**
     * Returns the cleaned up uri
     * @return string
     */
    private function getVirtualUri()
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

    /**
     * Checks all valid forms of a virtualUri and tries to find a matching file
     * @return string
     */
    public function lookupFile()
    {
        if (is_file($this->basePath . $this->virtualUri . '.md')) {
            $file = $this->basePath . $this->virtualUri . '.md';
            return $file;
        } elseif (is_file($this->basePath . str_replace('.html', '', $this->virtualUri) . '.md')) {
            $file = $this->basePath . str_replace('.html', '', $this->virtualUri) . '.md';
            return $file;
            // todo needs to check if html exists and updateable
# Recognize all supported linking styles from possible markdown documents outside, using mdwiki or not converted by Up!
        } elseif (is_file($this->basePath . $this->virtualUri . '.html')) {
            // this should never be reached now
            $file = $this->basePath . $this->virtualUri;
            $checkNeedsUpdate = true;
            $redirectToHtml = true;
            return $file;
        } elseif (is_file($this->basePath . str_replace('.md', '', $this->virtualUri) . '.html')) {
            $file = $this->basePath . str_replace('.md', '', $this->virtualUri);
            $checkNeedsUpdate = true;
            $redirectToHtml = true;
            return $file;
        } elseif (is_file($this->basePath . $this->virtualUri)) {
            $file = $this->basePath . $this->virtualUri;
            return $file;
            // TODO: remove! This (#!) isn't working, it's not possible to parse the fragment at all, so we only can change url's we encounter in documents, but not serve any that is called this way
            // TODO #2: Once a page is created (as example index.html) a javascript might be able to recognize if (index.html#!something.md) were called and sent this to the server recognizable
        } elseif (is_file($this->basePath . str_replace('#!', '', $this->virtualUri))) {
            $file = $this->basePath . str_replace('#!', '', $this->virtualUri);
            return $file;
        } elseif (is_file($this->basePath . $this->virtualUri . '/index.md')) {
            $file = $this->basePath . $this->virtualUri . '/index.md';
            return $file;
        } elseif (is_file($this->basePath . '/404.md')) { // TODO: Separate "not found" logic from looking up file
            $file = $this->basePath . '/404.md';
            return $file;
        } else {
            echo '404 File not found'; // TODO: No output!
            return $file; // TODO: Improve!
        }
    }

}
