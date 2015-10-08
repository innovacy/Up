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

    /** @var array Configuration */
    protected $config = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        // Warning: basepath is wrong if class would be moved elsewhere (TODO?)
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
        header('Content-Type: text/html; charset=utf-8');

        if (!$file = $this->discoverFile($this->virtualUri)) {
            $file = $this->discoverFile($this->virtualUri, true, '404');
            header("HTTP/1.0 404 Not Found");
            if (!$file) {
                die('404 File not found');
            }
        }

        $meta = '<meta name="generator" content="Up!">';
        // load generic configuration in main directory if exists
        if ($fileConfig = $this->discoverFile('config.json')) {
            $this->config = array_merge($this->config, json_decode(file_get_contents($fileConfig), true));
        }
        // allow overriding configuration in a subdirectory, also partially. Only takes the first file found in parents
        if ($fileConfig = $this->discoverFile($this->virtualUri, true, 'config.json')) {
            $this->config = array_merge($this->config, json_decode(file_get_contents($fileConfig), true));
        }

        if (!empty($this->config['loadCss'])) {
            if ($fileCss = $this->discoverFile($this->virtualUri, true, $this->config['loadCss'])) {
                $meta .= '<link rel="stylesheet" type="text/css" href="'.
                    str_replace($this->basePath, '', $fileCss).'">';
            }
        }
        $markdown = file_get_contents($file);
        $markup = $this->parserMain->parse($markdown);

        $navigation = $this->parserNavigation->parse(file_get_contents($this->basePath .'/navigation.md'));

        $footer = '';
        if ($fileFooter = $this->discoverFile($this->virtualUri, true, 'footer')) {
            $footer = $this->parserFooter->parse(file_get_contents($fileFooter));
        };

        $reflector = new \ReflectionClass(get_class($this));
        $tpl = file_get_contents(dirname($reflector->getFileName()).'/page.tpl');
        $tpl = str_replace('{$meta}', $meta, $tpl);
        $tpl = str_replace('{$markup}', $markup, $tpl);
        $tpl = str_replace('{$navigation}', $navigation, $tpl);
        $tpl = str_replace('{$footer}', $footer, $tpl);
        return $tpl;
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
        $pathBits['dirname'] = '/'.ltrim($pathBits['dirname'], '/');
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
            $pathBits['filename'] = $_p['filename'];
            $pathBits['extension'] = isset($_p['extension']) ? $_p['extension'] : '';  // avoid warning when undefined
        }
        $pathBits['extension'] = !isset($pathBits['extension']) ? '' : $pathBits['extension'];

        $paths = array_reverse($paths);
        foreach ($paths as $path) {
            if (empty($pathBits['extension'])) {
                // uri without extension called, look for md first, html second
                if (is_readable($path.$pathBits['filename'].'.md')) {
                    return $path.$pathBits['filename'].'.md';
                } elseif (is_readable($path.$pathBits['filename'].'.html')) {
                    return $path.$pathBits['filename'].'.html';
                }
            } elseif ($pathBits['extension'] == 'html' || $pathBits['extension'] == 'md') {
                // always prefer md over html if both exist, but uri could have been called as html
                if ($pathBits['extension'] == 'html'
                    && is_readable($path.$pathBits['filename'].'.md')
                ) {
                    return $path.$pathBits['filename'].'.md';
                // next, check if html or md was requested and if it exists
                } elseif (($pathBits['extension'] == 'html' || $pathBits['extension'] == 'md')
                    && is_readable($path.$pathBits['filename'].'.'.$pathBits['extension'])
                ) {
                    return $path.$pathBits['filename'].'.'.$pathBits['extension'];
                // last, look for html if md was requested but not found (i.e. old link referring, changed to new file)
                } elseif ($pathBits['extension'] == 'md'
                    && is_readable($path.$pathBits['filename'].'.html')
                ) {
                    return $path.$pathBits['filename'].'.html';
                }
            } else {
                // so we can search resursively for other file types for other purposes too
                if (is_readable($path.$pathBits['filename'].'.'.$pathBits['extension'])) {
                    return $path . $pathBits['filename'] . '.' . $pathBits['extension'];
                }
            }
        }
        // we just return false, leave 'not found' decisions and handling for caller
        return false;
    }
}

/** TODO: For mdwiki-style links #!name.md and making them crawlable, speak returning the rendered version when links
 * on a different server couldn't have been modified by Up!,
 * see https://developers.google.com/webmasters/ajax-crawling/docs/specification,
 * Quick infos: https://prerender.io/js-seo/angularjs-seo-get-your-site-indexed-and-to-the-top-of-the-search-results/
 */

/** To Decide: Force redirect *.md reqeusts to .html? Or leave them as is? Or leave that as a task for .htaccess? */
