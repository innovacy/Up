// TODO below here
....

You probably would want to use some custom styling with your custom footer, as example to arrange 
your custom footer navigation links in a specific way. Read [here](custom-styles.md) how to add custom styling.





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


