
## Advanced Features


## Page not found (404)

If the requested page is not found, a 404.md or a 404.html file will be searched in the requested path.

If no such file is found, the parent directories up to the location of the script will be searched recursively for such
a file.

A 404.md has priority over a 404.html file, so if you want to have a html file loaded and served, make sure you remove
the 404.md file.

If no 404 file is found, a simple "File not found" error will be shown.


## Using a custom footer

A custom footer can be added to pages. A footer.md or a footer.html will be searched in the requested path.

If no such file is found, the parent directories up to the location of the script will be searched recursively for such
a file.

A footer.md has priority over a footer.html, so make sure there is no footer.md is present or is found before your
preferred footer.html file.

You can add a different custom footer for some pages by creating one in a child folder. All pages in this folder and
their child folders will have this footer.


### Advanced custom footer (work in progress)

Alternatively to a footer.md file, the parser looks first for a footer.html and if it finds one, it will use this one
instead. This allows you to add any html code. It can also contain bootstrap classes and features, as also jQuery.
You don't need to include any of these libraries yourself, they are already automatically loaded.

### Using a custom style (work in progress)

Up! adds classes and id's to sections like navigation and footer. This way, you can change the look of any elements
of the final pages.

To add custom styles, simply create a custom.css file in the main folder. It will be loaded after any other styles.

For now, as no such list exists, inspect the generated files to see what classes and id's are available for
custom styling.


### Custom modifications for all pages

You want to add your own modifications in the html that is generated? Nothing easier than that. Find the file
page.tpl in the Up folder and edit it as you wish. You only need to keep the variables like {$meta} in place.








### Changing settings with config.json

You can have a config.json file in the main folder and in any of the subdirectories.

Up! looks first in the main folder for config.json file and will load it. Then it will search resursively beginning
with requested path all parents folders for a config.json. The first file that will be found, will be loaded and can
overwrite settings of the main config.json file. Only one additional config.json will be loaded, other files further up
in the folder structure will be ignored.

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
