## Adding navigation

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

