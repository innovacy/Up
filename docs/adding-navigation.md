# Adding a navigation menu

Up! will add several navigations to your site:
  * Headline / Header anchors
  * A side navigation bar
  * A top navigation menu
  * Footer links


## Headline / Header anchors

A headline will cover the whole document above it's other content and the side navigation bar. 
To add such a headline, use a 1st level header - optimally as first line - in your document. 

    # Heading of the document
    
    Other content follows...

The first 1st level header will become the title of the document in the browser, so in most cases you would wand to have one. 

However, if you don't wish to display a headline, then simply don't add a 1st level header and to not leave the document title empty, 
you can use a default title in the config (see [Configuration](config.md)).

All headings from 2nd to 6th level are navigable too. If you hover over one of them, you will see a anchor-char appearing at it's end, 
which you can rightclick and copy the address like with any other link. This link can be used anywhere to refer directly to this section of the document.


## Side Navigation Bar

The side navigation bar is automatically created by the outline of the document displayed. 

To create a specific side navigation bar, add 2nd level headers to your document. First level headings are ignored in that outline, 
because of their special meaning described in the previous paragraph. 

If you don't wish to display a side navigation bar in your document, simply outline your document(s) 
with 3rd to 6th level headers and don't use 2nd level headers.

Alternatively, if you are an advanced developer, you can hide the side navigation bar by using a [custom style](custom-styles.md).


## Top navigation menu

The top navigation menu is optional and won't be rendered, unless you explicitly create one. 

Start by creating a `navigation.md` file in your main folder of your documents. 

Note: In theory, you can add any valid MarkDown in this document and it will be parsed, however you may end up 
with unexpected results for anything else described here. 

Tip: It is safe to add an image (like a logo) before or after the navigation, as long it's dimensions are 
correct as no scaling will be done, but it's not advised to do so anywhere else. If you want to style the navigation 
entries with icons and are an advanced developer, you should rather use [custom styling](custom-styles.md) instead. 

The navigation menu can render optionally a title, if present. To add a title to the navigation menu, 
add at the top of the file a 1st level header. 

Horizontal menus are created by simply adding MarkDown links pointing to the URL to open, when clicked. 
Make sure each link representing a horizontal menu is followed by a blank line. The url's can be of course any valid url.

This is how a simple `navigation.md` would look like:


    # My Wiki Site
    
    [Home](index.md)

    [First Page](page1.md)
    
    [Second Page](page2.md)
    
    [Visit us on twitter](http://twitter.com/username)

You can also create dropdowns. To create a dropdown, leave the URL of the specific entry empty and add a list 
with one MarkDown link per list entry below it. 

You can add a divider line in the dropdown by simply adding `- - - -` in the list. 

Also you can add a subtitle within the list, by simply adding a 1st level header instead of a MarkDown link. 

This is how a more complex `navigation.md` with these features would look like:

    # Company Wiki

    ![](images/companylogo.png)
    
    [Home](index.md)

    [Sample Dropdown]()

      * [First Submenu](first.md)
      * [Second Submenu](second.md)
      * [Divider below this](divider.md)
      - - - -
      * # Sub Title
      * [Another Submenu](another.md)

    [About](about.md)
    
    [Contact](contact.html)

It's really so simple! Up! will automatically do all the rest for you, like rendering, styling and adding any required functionality, creating a complete navigation. 
The dropdown of the above example will look similar to this, when you hover over "Sample Dropdown":

![](img/menu-sample-dropdown.png)


## Individual Navigations

Up! has an advanced feature that allows you to add more than one top navigation menu to your site or separate sections of your site. 

Please refer to [this document](multiple-navigation.md) for details. 


## Footer links

Footer links can be added in a slightly different way than the top navigation menu. The way to add these links is to 
create a custom footer, which is described [here](custom-footer.md). This gives you the flexibility of laying them out any way you want them. 

