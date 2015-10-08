# Up!
An extremely simple, yet powerful markdown-based CMS

Supports:

  * HTML5 and CSS3
  * Navigation
  * Bootstrap
  * Themes
  * jQuery
  * Search

... without writing one line of any code, HTML or CSS.

**WARNING:** This is a work in progress (where stated). This document describes for now the features of the very first planned release that will follow very shortly.

## There are hundreds markdown parsers, why one more?

Because there is none like this one. All markdown parsers I found generally take a markdown line and convert it.
They are usually not file-focused and anything more complex requires writing code.

Up! serves a very specific purpose: Not to just parse markdown, but easily create associated markdown files that
make up a complete website.

##### My problem was this:

I needed to write documentation for end users with three requirements:
   1. It should been easy editable by translators
   2. It should work system-independent, be effortless integrated in various systems and served to end users formatted
   3. I needed the markdown files to make up a structure (speak: navigation) and not just parsed

The solution to 1. was Markdown. One could say, I could have used Wordpress. But then, any system
I had liked to implement this documentation would depend on a wordpress installation and this was
a bad requirement. So I needed something to fulfill point 2. The solution to this was [mdwiki](http://www.mdwiki.info),
which can load and parse markdown in browser, so it was independent of anything installed on the server
or the system and technologies within which the same markdown would be implemented. And point 3 was
fulfilled by mdwiki too, as it's creating a navigation with the help of markdown, adds bootstrap, jquery,
CSS and more. Perfect!

Now, I had documentation from several software, which I served in any system (Wordpress, Prestashop, Smarty, Twig)
just with mdwiki. But I wanted to be able to combine these into one larger Helpdesk site with little to no additional
effort every time I add another software.

Though it's possible to have several separate navigations, languages and documentations, by dropping
separate mdwiki copies in the subdirectories, this would become soon become too complex and it would
still lack additional functionality or flexibility such a helpdesk would need, like search functionality,
search crawling for better SEO, better language handling and more.

##### The solution to this...

As far I could research, such a solution doesn't exist at the time of this writing! So "Up!" was born.

Up! integrates a markdown parser, keeps and extends a mkdwiki fully compatible structure to create similar pages,
that it stores as static html. Not only can the same files be used without modifications like this, but all previous
issues can be solved now with Up! and new features added to the pages.

However, Up! doesn't have to be used like in my special case with mdwiki. Up! in itself is a markdown-based CMS,
that allows anyone with extremely little effort to create complete sites with Navigation, CSS, Bootstrap, jQuery,
Themes, search functionality and in future maybe more functionality, without installing any CMS, knowing any
of these technologies and writing any HTML, CSS or other code.

The perfect solution, when the focus is the content, no matter if someone is an expert in web technologies
or has never installed or worked with Wordpress.

It's very simple, requires very little to learn and it takes less a minute to set up and takes care of everything.
As a bonus, it serves pages as fast as a server can serve static html pages.


## Getting Started (work in progress)

You'll find that everything is extremely simple to use and set up, even the advanced features are ridiculous easy to use.

As first step, you need to either download and extract the zip in an empty folder or clone the repository.

Now create a file index.md and start adding your text for the front page. Create more files as you need. You can
name them anything you want and also create subdirectories as you wish, to create any structure that serves your needs.
There are really no rules or limitations here.

That's all for a start! You could just start writing your documentation, your Helpdesk or your site right away
and concentrate on the content without worrying about anything else for now.

### Linking to other files (work in progress)

At some point, you'll probably want to link to other files in your markdown.

Say, you want to link to a file called Help.md, you can write any of the following lines:

    This a [link](Help) to Help.
    This a [link](Help.md) to Help.
    This a [link](Help.html) to Help.
    This a [link](!#Help.md) to Help. Btw, this is a mdwiki link.

Up! will properly recognize that Help.md exists and will replace it in the resulting html with a proper link.
The result will be always the same. For compatibility reasons you might want to stay though with "Help.md".
If you want to keep everything working with mdwiki, use "!#Help.md".


### Adding navigation (work in progress)

You can add anytime a navigation bar to your content. Create a file navigation.md in the folder you created your
index.md. In the navigation.md you create a structure like this:

    # Title

    [Menu Link 1](menu-link-1.md)

    [Menu Link 2](menu-link-2.md)

    [Drop Down]()

        * [Drop Down Link 1](drop-down-1.md]

        * [Drop Down Link 2](drop-down-1.md]

    [Help](Help.md)

    [Visit Innovacy](http://www.innovacy.com/)


Up! will create a complete navigation bar with a title, dropdown menus and links from this, fully styled with CSS.

You have the feature to create separate navigations for subfolders. By adding a navigation.md in the specific subfolder,
Up! will create a different navigation for all files in this subfolder and all subfolders.

This is optional though. If Up! can't find a navigation.md in the folder the md file exists, it will search the parent folders until it finds
one and use it.


### Page not found (404)

If the requested page is not found, a 404.md or a 404.html file will be searched in the requested path.

If no file is found, the parent directories up to the location of the script will be searched recursively for such
a file.

A 404.md has priority over a 404.html file, so if you want to have a html file loaded and served, make sure you remove
the 404.md file.

If no 404 file is found, a simple "File not found" error will be shown.


### Custom footer (work in progress)

You can add a custom footer to your pages. All you need to do is just create a footer.md file.

### Selecting a theme (work in progress)

Up! comes with support for several themes. (Documentation to be added)


## Advanced Features

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

#####  Settings to be documented (work in progress)

    {
      "useSideNav": "true",      // Won't create a side navigation if false
      "title": "",               // If set, will add this to the title of all pages, otherwise it will take the first header only
      "lineBreaks": "original",  // Can be "gfm" for Github flavoured line breaks
      "anchorCharacter": "#"     // Is shown on links
      "noCache": false           // If set to true, the content will be created every time, by default the cache automatically recognizes a necessary update.
      "cacheNoUpdates": false    // if set to true, no checks will be performed if a file was updated, once a md file was parsed. You will have to delete the cache manually.
    }

