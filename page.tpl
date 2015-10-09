<!doctype html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.png">
    {$meta}
    {$scripts}

    <style>
        .md-navbar-margin {
            margin-top: 60px;
        }
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
    </style>
</head>
<body>

    <div id="md-all">

        <div id="md-menu" class="{$hide_navigation}">
            <div id="md-main-navbar" class="navbar navbar-default navbar-fixed-top" role="navigation">

                {$navigation}
            </div>
        </div>

        <div class="container" id="md-body-container">
            <div class="row" id="md-body-row">
                <div id="md-body" class="md-navbar-margin{$hide_navigation}">

                    {$markup}

                </div>
            </div>
        </div>
    </div>

    <div class="container" style="position: relative; margin-top: 1em;">
        <div class="pull-right md-copyright-footer">
            <span id="md-footer-additional">
                {$footer}
            </span>
            Website generated with <a href="http://github.com/innovacy/up" target="_blank">Up!</a> &mdash; &copy; Innovacy, Dimitrios Karvounaris.
        </div>
    </div>

    {$scripts_footer}
</body>
</html>
