<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "vendor/autoload.php";
$up = new \Innovacy\Up();

$uri = $up->getVirtualUri();
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
echo $file;
echo $_SERVER['QUERY_STRING'];


$parser = new \cebe\markdown\Markdown();
$parser->html5 = true;
$markdown = file_get_contents($file);
$markup = $parser->parse($markdown);

echo <<<HTML
<!doctype html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="generator" content="Up!" />
	<style>
		body { font-family: Arial, sans-serif; }
		code { background: #eeeeff; padding: 2px; }
		li { margin-bottom: 5px; }
		img { max-width: 1200px; }
		table, td, th { border: solid 1px #ccc; border-collapse: collapse; }
	</style>
</head>
<body>
$markup
</body>
</html>
HTML;

