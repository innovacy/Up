<?php
/**
 * $Id$
 *
 * Up! - An extremely simple, yet powerful markdown-based CMS
 *
 * Copyright (c) 2015 Innovacy, Dimitrios Karvounaris
 *
 * @version 1.0.1
 * @copyright 2015 Innovacy - Dimitrios Karvounaris
 * @author Dimitrios Karvounaris, <d.karvounaris@innovacy.com>
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

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

ini_set('default_charset', 'utf-8');

require __DIR__.'/src/Up/Autoloader.php';

$autoloader = new \Innovacy\Up\Psr4Autoloader();
$autoloader->register();
$autoloader->addNamespace('Innovacy\Up', __DIR__.'/src/Up');
$autoloader->addNamespace('cebe\markdown', __DIR__.'/src/markdown');

$app = new \Innovacy\Up\App();
$html = $app->run();
echo $html;
