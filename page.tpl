<!doctype html>
<!--
* $Id$
*
* Up! - An extremely simple, yet powerful markdown-based CMS
*
* Copyright (c) 2015 Innovacy, Dimitrios Karvounaris
*
* @version 2.0.0
* @copyright 2015 Innovacy - Dimitrios Karvounaris
* @author Dimitrios Karvounaris, <d.karvounaris@innovacy.com>
* @license See LICENSE file.
* @url https://www.github.com/Innovacy/Up
*
* --
*
* NOTICE OF LICENSE
*
* Up! is licensed under the terms of the GNU AGPLv3 with additional terms and
* linking exceptions. The AGPLv3 license text can be found in the AGPLv3.txt file,
* the additional terms can be found in the LICENSE file.
*
-->
<html>
<head>
    <title>{$title}</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.png">
    {$meta}
    {$scripts}

    <style>
        .md-inpage-anchor .anchor-highlight {
            font-size: 0.7em;
            margin-left: 0.25em;
            opacity: 0;
            transition: all 500ms ease-in-out;
        }
        .md-inpage-anchor:hover .anchor-highlight {
            opacity: 1;
            transition: all 500ms ease-in-out;
        }
        #up-content-view-container {
            height: 100vh;
            overflow: hidden;
            padding-top: 52px;
        }
        #up-content-view {
            overflow: auto;
            height: 100%;
        }
        .md-copyright-footer {
            padding-top: 1.5em;
            padding-bottom: 1.5em;
            text-align: right;
        }
        #up-footer {
            margin-top: 2em;
        }
    </style>
</head>
<body>

    <div id="md-all">

        <div id="md-menu" class="{$hide_navigation}">
            <div id="md-main-navbar" class="navbar navbar-default navbar-fixed-top" role="navigation">
                {$navigation}
            </div>
        </div>

        <div id="up-content-view-container{$hide_navigation}"><div id="up-content-view{$hide_navigation}">

            <div class="container" id="md-body-container">
                <div class="row" id="md-body-row">
                    <div id="md-body">
                        {$markup}
                    </div>
                </div>
            </div>

            <div id="up-footer" class="md-copyright-footer">
                <div class="container">
                    <span id="md-footer-additional">
                        {$footer}
                    </span>
                    {$copyright}
                </div>
            </div>

        </div></div>

    </div>

    {$scripts_footer}
</body>
</html>
