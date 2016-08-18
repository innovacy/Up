# Advanced Features

## 404 File not found 

If a requested document is not found or it's name misspelled, Up! will send by default a 404 status to the browser 
and since version 2 render a default 404 page in the same way as any other document (including navigation, custom settings and so on).

The document will simply state: "We're sorry, but we couldn't find the requested document.".

You can modify this output, by simply creating your own `404.md` file in the main folder where you installed Up!
and it can contain any MarkDown content you wish to be displayed. 

Alternatively, you can create a `404.html` file and this one will be displayed as-is within the site's layout, 
whenever a document is not found. (see [Rendering HTML files](advanced-features.md#Rendering_HTML_files) for caveats).

If you created multiple sections on your site, you can override your 404 document for [each section](multiple-navigation.md#)


## Rendering HTML files

In general, Up! supports that you write your documents as HTML files instead in MarkDown.
Up! will then use these file as-is and will not parse them as MarkDown. It also will integrate them 
into your layout like any other MarkDown parsed file.

This works always where such a file is defined in a configuration or is searched automatically, like with the "404 Page not found" file.

However, whenever you will link to a document yourself, there are some caveats you should be aware of: 

The html files that you want to become part of your layout should contain only the partial html that should be displayed, 
but neither a `<head>´ section, nor html, meta or body tags or a document type declaration (Up! sets automatically HTML5).

By default, if you link from one of your files directly to a `.html` file like this: `[link](file.html)`, once you click 
on this link in the browser it will simply load the `.html` file directly without going through Up!. 
This is on purpose, so you can bypass Up! and the layout creation, whenever you explicitly want to load something else. 

If you want a `.html` file however to be part of the site and it's layout, but simply create your document as HTML
instead in MarkDown, you must link to it like this: `[link](file)` or this: `[link](file.md)`. Up! will then automatically
discover that there is no `.md` file and will look for a `.html` file and use this one instead. 

This implies, that you should not have a `.md` file with the same name in the same folder, if you want the `.html` file to be loaded.


### Forcing all HTML files and links rendered with Up!

Even though not recommended to change the default behaviour, as it's only an experimental feature, 
you can force all your `.html` files and links to them going through Up! instead of being served directly to the browser.

Warning: Make this change only if you know what you are doing!

In your .htaccess find and uncomment the following line:

    # RewriteCond %{REQUEST_URI} .*\.html [OR]



// TODO below here






#### Valid settings in config.json (work in progress)
If a config.json file exists, Up! will check it. It is mostly compatible with mdwiki's settings with small differences.

Currently supported are (the values shown are defaults):

##### config.json: loadCss

This setting indicates a CSS filename without a path to load. Up! then searches for this file starting from
the requested path through all parents. The first that is found is loaded in the site.

This way, you can overwrite your styles in every folder (incl. all it's child folders) you want to.

    {
        "loadCss": "mystyle.css"
    }

##### config.json: highlightJs

    {
        "highlightJs": false
    }

By default, the highlight.js javascript library is loaded for automatic code highlighting. However, if you have
no use of this feature for your pages or just don't want to use it, you can deactivate loading of any highlightJs
resources alltogether, by setting this config setting explicitly to false.

##### config.json: theme

    {
        "theme": "bootstrap"
    }

This setting sets a theme. In case there's a theme defined in navigation.md, this setting has priority over it.
The available styles and their names to use can be seen on http://bootswatch.com. The default bootstrap theme's name
is "bootstrap".

##### config.json: additionalFooterText

    {
        "additionalFooterText": "This site is created by Innovacy"
    }

Adds the text defined to the footer. This setting is overridden and does nothing, if a footer.md or footer.html file
is added instead.

##### config.json: lineBreaks

    {
      "lineBreaks": "original"  // Can be "gfm" for Github flavoured line breaks
    }

Can have either the value "original" or "gfm". The default value is "gfm".

If set to "original", line breaks in markdown are interpreted as <br />.
See https://help.github.com/articles/writing-on-github/ for further explanation regarding newlines.

##### config.json: gAnalytics

    {
      "gAnalytics": "UA-45601234-1"
    }

This setting adds automatically all required Google Analytics tracking to all generated sites.
It accepts your Analytics Property Tracking ID as parameter.

#####  Settings to be documented (work in progress)

    {
      "anchorCharacter": "#"     // Is shown on links
    }
