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
        if (!$file = $this->discoverFile($this->virtualUri)) {
            $file = $this->discoverFile($this->virtualUri, true, '404');
        }

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
     * Checks all valid forms of a virtualUri and tries to find a matching file on disk.
     * Leave out the extension on $relativePath or $overrideFile, to search for all options
     * @param string $relativePath Relative path, optionally including a filename to lookup
     * @param bool|false $recursive
     * @param null|string $overrideFile Overrides an optional file on $relativePath with $overrideFile
     * @return bool|string
     */
    protected function discoverFile($relativePath, $recursive = false, $overrideFile = null)
    {
        $relativePath = ($relativePath == '') ? '/' : $relativePath;
        if (is_dir($this->basePath.$relativePath)
            && !is_file($this->basePath.$relativePath)
            && substr($relativePath, -1) != "/"
        ) {
            $relativePath .= '/';
        }
        // taking care of 'index' here makes separate comparisons later obsolete
        $relativePath .= (substr($relativePath, -1) == '/') ? 'index' : '';
        $pathBits = pathinfo($relativePath);
        $pathBits['dirname'] = str_replace('\\', '', $pathBits['dirname']); // windows
        $pathBits['dirname'] = ltrim($pathBits['dirname'], '/');
        $paths = array();
        if ($recursive) {
            // construct a list of all parent paths
            $ls = explode('/', $pathBits['dirname']);
            $e = '';
            foreach ($ls as $l) {
                $e .= $l . '/';
                $paths[] = $this->basePath.$e;
            }
        } else {
            $paths[] = $this->basePath.rtrim($pathBits['dirname'], '/').'/';
        }

        if (!empty($overrideFile)) {
            $_p = pathinfo($overrideFile);
            $pathBits['basename'] = $_p['basename'];
            $pathBits['extension'] = isset($_p['extension']) ? $_p['extension'] : '';  // avoid warning when undefined
        }
        $pathBits['extension'] = !isset($pathBits['extension']) ? '' : $pathBits['extension'];

        $paths = array_reverse($paths);
        foreach ($paths as $path) {
            if (empty($pathBits['extension'])) {
                // uri without extension called, look for md first, html second
                if (is_readable($path.$pathBits['basename'].'.md')) {
                    return $path.$pathBits['basename'].'.md';
                } elseif (is_readable($path.$pathBits['basename'].'.html')) {
                    return $path.$pathBits['basename'].'.html';
                }
            } else {
                // always prefer md over html if both exist, but uri could have been called as html
                if ($pathBits['extension'] == 'html'
                    && is_readable($path.$pathBits['basename'].'.md')
                ) {
                    return $path.$pathBits['basename'].'.md';
                // next, check if html or md was requested and if it exists
                } elseif (($pathBits['extension'] == 'html' || $pathBits['extension'] == 'md')
                    && is_readable($path.$pathBits['basename'].$pathBits['extension'])
                ) {
                    return $path.$pathBits['basename'].$pathBits['extension'];
                // last, look for html if md was requested but not found (i.e. old link referring, changed to new file)
                } elseif ($pathBits['extension'] == 'md'
                    && is_readable($path.$pathBits['basename'].'.html')
                ) {
                    return $path.$pathBits['basename'].$pathBits['html'];
                }
            }
        }
        // we just return false, leave 'not found' decisions and handling for caller
        return false;
    }
}
