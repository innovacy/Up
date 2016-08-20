<?php
/**
 * $Id$
 *
 * Up! - An extremely simple, yet powerful markdown-based CMS
 *
 * Copyright (c) 2015-2016 Innovacy, Dimitrios Karvounaris
 *
 * @version 1.99.99
 * @copyright 2015-2016 Innovacy - Dimitrios Karvounaris
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
 * Class Up
 * @package Innovacy
 */
class App
{
    /** @var string Up! Release */
    public $version = '1.99.99';
    protected $virtualUri;
    /** @var Navigation The parser class */
    protected $parserNavigation;

    /** @var MarkDown The parser class */
    protected $parser;

    /** @var MarkDown The parser class */
    protected $parserFooter;

    /** @var string Base local path */
    protected $basePath;

    /** @var string Holds the output */
    protected $output;

    /** @var array Configuration */
    protected $config = array(
        'useSideNav' => true,
        'lineBreaks' => 'gfm',
        'highlightJs' => true,
        'theme' => ''
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->basePath = $this->getBasePath();
        $this->virtualUri = $this->getVirtualUri();

        $this->parser = new Markdown();
        IoC::register('parser', $this->parser);
        $this->parser->html5 = true;

        $this->parserNavigation = new Navigation();
        IoC::register('navigation', $this->parserNavigation);
        $this->parserNavigation->html5 = true;

        $this->parserFooter = new Markdown();
        IoC::register('footer', $this->parserFooter);
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
        }
        if (!empty($file)) {
            $markdown = file_get_contents($file);  // this can be also a 404.md
        } else {
            $markdown = <<<END_FILE_NOT_FOUND
# 404 Document not found

We\'re sorry, but we couldn\'t find the requested document.
END_FILE_NOT_FOUND;
        }

        $this->output = $markdown;
        $meta = '<meta name="generator" content="Up!">';
        $scripts =
            '<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>' .
            '<script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>';
        $scripts_footer = '';

        // load generic configuration in main directory if exists
        if ($fileConfig = $this->discoverFile('config.json')) {
            $this->config = array_merge($this->config, json_decode(file_get_contents($fileConfig), true));
        }
        // allow overriding configuration in a subdirectory, also partially. Only takes the first file found in parents
        if ($fileConfig = $this->discoverFile($this->virtualUri, true, 'config.json')) {
            $this->config = array_merge($this->config, json_decode(file_get_contents($fileConfig), true));
        }

        if (!empty($this->config['loadCss'])) {
            $cssUri = $this->config['loadCss'];
            // is it an absolute url?
            if (preg_match('#^(\w+:)?//#', $cssUri)) {
                $meta .= '<link rel="stylesheet" type="text/css" href="' . $cssUri . '">';
            } elseif ($fileCss = $this->discoverFile($this->getVirtualUriFromFile($fileConfig), true, $cssUri)) {
                $meta .= '<link rel="stylesheet" type="text/css" href="' .
                    str_replace($this->basePath, '', $fileCss) . '">';
            }
        }
        if (!empty($this->config['gAnalytics'])) {
            $scripts_footer .= "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', '" . $this->config['gAnalytics'] . "', 'auto');
  ga('send', 'pageview');
</script>";
        }
        if ($this->config['highlightJs']) {
            $meta .=
                '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.8.0/styles/default.min.css">'
                . '<style type="text/css">.hljs {background:transparent;}</style>';
            $scripts .= '<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.8.0/highlight.min.js"></script>';
            $scripts_footer .= <<<HIGHLIGHTJS
    <script type="text/javascript">
        $(document).ready(function() {
            $('pre code').each(function(i, block) {
                hljs.highlightBlock(block);
            });
        });
    </script>
HIGHLIGHTJS;
        }

        $this->parser->useSideNav = $this->config['useSideNav'];
        $this->parser->enableNewlines = $this->config['lineBreaks'] == 'original';
        if (!preg_match('#\.html$#', $file)) { // TODO: simply not very elegant and error-prone
            $markup = $this->parser->parse($markdown);
        } else {
            $markup = $markdown;  // do not parse html files, display them as-is
        }

        $navigation = '';
        $hide_navigation = '';
        if ($fileNav = $this->discoverFile($this->virtualUri, true, 'navigation.md')) {
            if (is_readable($fileNav)) {
                $navContent = file_get_contents($fileNav);
                if (preg_match(
                    '/\[gimmick\:theme\s*(\(inverse\:\s*(false|true)\))?\]\(([a-z]+)\)/',
                    $navContent,
                    $matches
                )) {
                    $navContent = str_replace($matches[0], '', $navContent);
                    $this->config['theme'] = empty($this->config['theme']) ? $matches[3] : $this->config['theme'];
                }
                $navigation = $this->parserNavigation->parse($navContent, $this->getVirtualUriFromFile($fileNav));
            }
        } else {
            $hide_navigation = 'hide';
        }
        if (empty($this->config['theme']) || $this->config['theme'] == 'bootstrap') {
            $meta .= '<link rel="stylesheet" type="text/css" ' .
                'href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">';
        } else {
            $meta .= '<link rel="stylesheet" type="text/css" ' .
                'href="//netdna.bootstrapcdn.com/bootswatch/3.3.5/' . $this->config['theme'] . '/bootstrap.min.css">';
        }

        $footer = '';
        if ($fileFooter = $this->discoverFile($this->virtualUri, true, 'footer')) {
            $footer = $this->parserFooter->parse(file_get_contents($fileFooter));
        } elseif (isset($this->config['additionalFooterText'])) {
            $footer = $this->config['additionalFooterText'];
        }

        if (!file_exists($this->basePath . '/page.tpl')) {
            throw new \RuntimeException('File page.tpl not found, but is required');
        }
        $tpl = file_get_contents($this->basePath . '/page.tpl');
        $tpl = str_replace(
            '{$title}',
            ((!$titleEmpty = empty($this->parser->title))
                ? $this->parser->title : '') .
            (isset($this->config['title'])
                ? ($titleEmpty ? '' : ' - ') . $this->config['title'] : ''),
            $tpl
        );
        $tpl = str_replace('{$meta}', $meta, $tpl);
        $tpl = str_replace('{$scripts}', $scripts, $tpl);
        $tpl = str_replace('{$scripts_footer}', $scripts_footer, $tpl);
        $tpl = str_replace('{$markup}', $markup, $tpl);
        $tpl = str_replace('{$navigation}', $navigation, $tpl);
        $tpl = str_replace('{$hide_navigation}', $hide_navigation, $tpl);
        $tpl = str_replace('{$footer}', $footer, $tpl);
        if (strpos($tpl, '{$copyright}') === false) {
            die('You are not allowed to remove the copyright footer notice by the software\'s license. 
                Please contact the author for a commercial license allowing you to remove the copyright footer.');
        }
        $copyright = '<p>Website generated with <a href="http://github.com/innovacy/up" target="_blank">Up!</a> ' .
            '&mdash; &copy; 2015-' . date('Y') . ' Innovacy, Dimitrios Karvounaris.</p>';
        $tpl = str_replace('{$copyright}', $copyright, $tpl);
        $this->output = $tpl;
    }

    public function output()
    {
        echo $this->output;
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
     * Returns an uri path relative to the file given
     * @param $filename
     * @return mixed
     */
    private function getVirtualUriFromFile($filename)
    {
        if (is_file($filename)) {
            $pathBits = pathinfo($filename);
            $dir = $pathBits['dirname'];
        } else {
            $dir = rtrim($filename, '/') . '/';
        }
        $document_root = isset($_SERVER['CONTEXT_DOCUMENT_ROOT'])
            ? $_SERVER['CONTEXT_DOCUMENT_ROOT'] : $_SERVER['DOCUMENT_ROOT'];
        $path = str_replace($document_root, '', $dir);
        $virtualUri = rtrim('/' . ltrim(str_replace('\\', '/', $path), '/'), '/') . '/';
        return $virtualUri;
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
        if (is_dir($this->basePath . $relativePath)
            && !is_file($this->basePath . $relativePath)
            && substr($relativePath, -1) != "/"
        ) {
            $relativePath .= '/';
        }
        // taking care of 'index' here makes separate comparisons later obsolete
        $relativePath .= (substr($relativePath, -1) == '/') ? 'index' : '';
        $pathBits = pathinfo($relativePath);
        $pathBits['dirname'] = str_replace('\\', '', $pathBits['dirname']); // windows
        $pathBits['dirname'] = '/' . ltrim($pathBits['dirname'], '/');
        $paths = array();
        if ($recursive) {
            // construct a list of all parent paths
            $ls = explode('/', $pathBits['dirname']);
            $e = '';
            foreach ($ls as $l) {
                $e .= $l . '/';
                $paths[] = $this->basePath . $e;
            }
        } else {
            $paths[] = $this->basePath . rtrim($pathBits['dirname'], '/') . '/';
        }

        $extraPath = '';
        if (!empty($overrideFile)) {
            $_p = pathinfo($overrideFile);
            $extraPath = rtrim(ltrim(str_replace('\\', '', $_p['dirname']), '/'), '/') . '/';
            $pathBits['basename'] = $_p['basename'];
            $pathBits['filename'] = $_p['filename'];
            $pathBits['extension'] = isset($_p['extension']) ? $_p['extension'] : '';  // avoid warning when undefined
        }
        $pathBits['extension'] = !isset($pathBits['extension']) ? '' : $pathBits['extension'];

        $paths = array_reverse($paths);
        foreach ($paths as $path) {
            if (empty($pathBits['extension'])) {
                // uri without extension called, look for md first, html second
                if (is_readable($path . $pathBits['filename'] . '.md')) {
                    return $path . $pathBits['filename'] . '.md';
                } elseif (is_readable($path . $pathBits['filename'] . '.html')) {
                    return $path . $pathBits['filename'] . '.html';
                }
            } elseif ($pathBits['extension'] == 'html' || $pathBits['extension'] == 'md') {
                // always prefer md over html if both exist, but uri could have been called as html
                if ($pathBits['extension'] == 'html'
                    && is_readable($path . $extraPath . $pathBits['filename'] . '.md')
                ) {
                    return $path . $extraPath . $pathBits['filename'] . '.md';
                    // next, check if html or md was requested and if it exists
                } elseif (($pathBits['extension'] == 'html' || $pathBits['extension'] == 'md')
                    && is_readable($path . $extraPath . $pathBits['filename'] . '.' . $pathBits['extension'])
                ) {
                    return $path . $extraPath . $pathBits['filename'] . '.' . $pathBits['extension'];
                    // last, look for html if md was requested but not found (i.e. old link referring, changed to new file)
                } elseif ($pathBits['extension'] == 'md'
                    && is_readable($path . $extraPath . $pathBits['filename'] . '.html')
                ) {
                    return $path . $extraPath . $pathBits['filename'] . '.html';
                }
            } else {
                // so we can search resursively for other file types for other purposes too
                if (is_readable($path . $extraPath . $pathBits['filename'] . '.' . $pathBits['extension'])) {
                    return $path . $extraPath . $pathBits['filename'] . '.' . $pathBits['extension'];
                }
            }
        }
        // we just return false, leave 'not found' decisions and handling for caller
        return false;
    }

    /**
     * @return string
     */
    private function getBasePath()
    {
        return rtrim(
            isset($_SERVER['CONTEXT_DOCUMENT_ROOT'])
                ? $_SERVER['CONTEXT_DOCUMENT_ROOT']
                : (
            isset($_SERVER['DOCUMENT_ROOT'])
                ? $_SERVER['DOCUMENT_ROOT']
                : str_replace($_SERVER['PHP_SELF'], '', $_SERVER['SCRIPT_FILENAME'])
            )
            , '/');
    }
}
