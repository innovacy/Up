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


### Adding navigation

You can add anytime a navigation bar to your content. Create a file navigation.md in the folder you created your
index.md. In the navigation.md you create a structure like this:

    [Home](index.md)

    [Sample Dropdown]()

      * [First Submenu](first.md)
      * [Second Submenu](second.md)
      * [Divider below this](divider.md)
      - - - -
      * # Sub Title
      * [Another Submenu](another.md)

    [Page 1](page1.md)
    [Page 2](page2.md)

    [Visit Innovacy](http://www.innovacy.com/)

Up! will create a complete navigation bar with a title, dropdown menus and links from this, fully styled with CSS.

This would create a menu that looks like this:

![](img/menu-sample.png "Navigation")

and a dropdown like this:

![](img/menu-sample-dropdown.png)


You have the feature to create separate navigations for subfolders. By adding a navigation.md in the specific subfolder,
Up! will create a different navigation for all files in this subfolder and all subfolders.

This is optional though. If Up! can't find a navigation.md in the folder the md file exists, it will search the parent folders until it finds
one and use it.


### Page not found (404)

If the requested page is not found, a 404.md or a 404.html file will be searched in the requested path.

If no such file is found, the parent directories up to the location of the script will be searched recursively for such
a file.

A 404.md has priority over a 404.html file, so if you want to have a html file loaded and served, make sure you remove
the 404.md file.

If no 404 file is found, a simple "File not found" error will be shown.


### Using a custom footer

A custom footer can be added to pages. A footer.md or a footer.html will be searched in the requested path.

If no such file is found, the parent directories up to the location of the script will be searched recursively for such
a file.

A footer.md has priority over a footer.html, so make sure there is no footer.md is present or is found before your
preferred footer.html file.

You can add a different custom footer for some pages by creating one in a child folder. All pages in this folder and
their child folders will have this footer.


### Selecting a theme

There are two methods to set the theme:
  * In the config.json (new method, preferred, works also without navigation)
  * In the navigation.md (backwards compatible to mdwiki)

Up! comes with support for several bootstrap themes. For the preferred method, look under config.json:theme.
The following text describes only the theme feature available in navigation.md.

This works the same as in mdwiki. In the file navigation.md add a line like this:

    [gimmick:theme](theme-name)

The supported themes and their names can be found at http://bootswatch.com/ where you can see all their styles too.
Just replace 'theme-name' in the above line with your choice of theme. The default bootstrap theme can now be choicen
with "bootstrap".

If this line in navigation.md is missing, the default bootstrap look will be loaded.

The setting in config.json has priority over the one in navigation.md.

Note: This is a simplified mdwiki syntax, the full mdwiki syntax is also supported, however the inverse-attribute
isn't applied.

### Alert gimmick

Whenever a paragraph starts with a special trigger word that is followed by a colon `:` or exclamation mark `!`,
they are rendered as alert boxes.

These trigger words are case insensitive and are:

Type       | Trigger
-----------|---------
Warning    |warning, achtung, attention, warnung, atenci�n, guarda, advertimiento
Note       |note, beachte
Hint       |hint, tip, tipp, hinweis

