# Getting Started

You'll find that everything is extremely simple to use and set up, even the advanced features should be as easy.

In your installation you can browse the docs folder (yes, this documentation, as it's written in MarkDown and 
adds Up! functionality) to see examples of how to write yours or experiment around. 

It is easy to understand most things by just looking on the source files of the documentation and how they are rendered, 
if you prefer that over reading documentation.

## First Steps

Start by creating an index.md in the main folder. This will become your frontpage. This also will ensure that no 
"page not found" appears any longer, when the installation path is called without a filename specified. 
The latter applies also, to all directories you may create. 

From here you can name and structure your files and your directories any way you see fit for your site. Up! imposes nearly no rules or limitations in that matter.

Note: You cannot use however 404.md, navigation.md and footer.md as regular filenames, as they have a special meaning for Up! features. 

## MarkDown Syntax

To learn specifics about the MarkDown language, you should read one or all of the following sources:

  * http://daringfireball.net/projects/markdown/basics
  * https://guides.github.com/features/mastering-markdown/
  
Up! uses the Github flavoured markdown syntax, so you would want to refer 
to https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet for a complete reference after learning the basics. 

Exceptions and extensions are handled in this documentation.

A simple example shows how easy it is to format and outline a document in MarkDown:

    # This is a headline
    
    This is a paragraph that can span several lines, 
    several lines... 
    
    ## This is a second level header
    
    ### Third level header
    
    You can specify up to 6th level headers this way.
     
    This is an alternative way of a headline
    ================================
    
    This is an alternative way of a second level header
    -------------
    
    I am a link with an [anchor text, so click me](http://www.innovacy.com/)
    The guy coming after me is simply an automatic link pointing to http://www.innovacy.com without anchor text
    
       * This is an unordered list.
       * Notice the two spaces in front.
         * Nothing wrong with me, just a list under another list
    
       1. You can also specify ordered/numbered lists.
       3. simply followed by a horizontal dividing line
       
     *****************
     
     I am so **bold** to just refer you to the other documents to further study MarkDown syntax, but I'm not *italic*. 

This will be rendered similar to (a dark style was choosen simply to separate it clearly here): 

![](img/markdown-example.png)


For a truely extensive example, check the file [test.md](test.md)


## Setting document title in browser

Up! will pick (as far it will find one) the first 1st level headline as the title of the page for browsers and search engines.

If you have none specified and you have a title specific in the config, it will pick this one (see [Configuration](configuration.md#title).

If there is a headline and a title in the config, it will append the title from the config to the headline and use the combined one as title.
This allows to have some common title text throughout the whole site.


## Linking to other documents

At some point, you'll want to directly link to other documents in your MarkDown. 

Refer to https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet#links for a complete reference.

Say, you want to link to a file called Help.md, Up! understands and resolves all of these file notations:

    This a [link](Help.md) to Help. This is the default all-compatible way.
    This a [link](Help) to Help.
    This a [link](Help.html) to Help.

No matter which you prefer to use, Up! will always resolve to the proper document and display it. 
However, if compatibility with other parsers is important to you, you should use only the first syntax in your documents. 
One such case would be, if you want your document linking to work on GitHub when displayed there.
If this is not important to you, the `.html` syntax looks probably most appealing.

Note: Be aware, since you can add and link any other files, even html files, if you have added two files 
like `Help.md` and `Help.html`, the `.html` file with the same name will have priority over a `.md` file and 
will be the one called in the browser, if you have linked to it with the extension `.html`. 

Attention: See also [Rendering HTML files](advanced-features.md#Rendering_HTML_files) for another caveat with the non-default way of linking to html files.

**Only important for those upgrading from mdwiki:**

Previously, mdwiki-style linking was planned to be supported, this version doesn't though, because it turned to be problematic 
to be parsed server-side when clicked in browser, and like too much unnecessary overhead to have a workaround in the browser. 

So the following syntax with a `!#` is not supported and should not be used:

    This a [link](!#Help.md) to Help. This is a mdwiki-style link.

However, this style of links was only created by mkdwiki automatically when rendering the documents, and it still recognizes documents 
with the default way of linking, so unless you used it explicitly yourself in previous documents, it should impose no compatibility issue. 
