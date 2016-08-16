# A simple, fast and powerful markdown-based CMS/Wiki

Up! is an extremely simple, yet powerful markdown-based CMS/Wiki, to create easily and fast 
the most light-weight and fastest wiki's, documentations or simple websites, without writing 
one line of any PHP code, HTML or CSS. 


## Features

  * Uses [Markdown](http://daringfireball.net/projects/markdown/basics) (an easy-to-read, easy-to-write plain text format), which ... 
  * ... it automatically converts to a fully styled and navigational wiki-like site.
  * Simply focus on the authoring task, not on coding or styling
  * Create any individual navigation including dropdown menus
  * Uses HTML5, CSS3, jQuery, Bootstrap
  * Select from several ready-to-use themes
  * Requires no knowledge of these technologies by the author
  * No complex installations and no database required

### Also supports:

  * Structure your documents and navigation any way you want them
  * Create several depths of different sections each with it's navigation, if needed
  * Create a multi-language wiki easily
  * Can be fully crawled and indexed by search engines
  * Custom styles (if you really need to add some CSS)
  * Custom footer and 404 "page not found"
  * Add Google Analytics by simply adding your property ID (don't worry about implementation details)
  * Activate code syntax highlighting
  * Gimmicks advance Markdown with rich client functions like alerts and youtube video integration
  * Advanced users can also modify the default page layout html

### Planned features in near future:

  * Site search


## How is Up! different from other MarkDown parsers?

The main difference is, that almost every MarkDown parser is like a raw tool. It takes a piece of 
MarkDown text, parses and converts it into another format or for the browser. 

However, these ones are rarely file-focused, one needs to write additional code to even have a file read, parsed and stored or served to the browser.

Nearly none of them deals with multiple files (some not even with files), let alone with document structuring, navigation, styling and more. 

This is what Up! does, it provides a simple ready-to-use solution to create a fully styled and navigable wiki/site 
as simple and fast as possible, while allowing the author to focus almost completely on the actual task of authoring. Just 
get your wiki quickly Up! ;)


## Documentation

The documentation has been split from this document into the docs folder, for two reasons:

  * To be easier to read
  * To provide an example implementation of how you can use Up!

Once you install Up!, you can browse the documentation by opening http://<yourhost>/docs/ in your browser 
and you can use these files as starting point to modify and experiment with.

Browse the documentation:

  * [Getting started](docs/getting-started.md)
  * [Nagivation]()
  * [Configuration]()
  * [Advanced Features](docs/advanced-features.md)
  * [FAQ](docs/faq.md)
  

## Installation

Installation is simple and completed in less a minute. PHP 5.4 or higher is required to use it.

After cloning the repository, installation is recommended to be done via composer by running:

    composer install

A manual installation is not recommended, as you will need to add an autoloader yourself then or require() all classes. 

A zip-file without composer requirements will be published with the next release. 


## History

Up! started as an drop-in replacement for [MDwiki](http://dynalon.github.io/mdwiki/), because I needed a tool to 
provide documentation without additional installations to clients who purchased modules to use within another software. 

MDWiki served me at beginning well. However, I wanted to provide that same documentation under a common domain, speak have 
them all into a helpdesk-like site. Having several MDWiki installations for each one would been overkill and too much maintenance. 
Also, I would not be able to combine them all into one navigation without maintaining several versions, 
one for the central helpdesk and one for the one included in the modules purchased by clients. 

Further, MDWiki sites have some drawbacks, the most important is that they can't be indexed by search engines at all. 
But it was important that someone searching for the articles would find them in Google&Co.

As such, I created Up! that would render the sites before sending them to the browser instead of doing it in Javascript like MDWiki, 
would allow search engines to crawl everything, would allow to have it splitted into sections and combined them as needed with each 
section having their own individual navigation. 

Up! is as light-weight as MDWiki, it is so lightweight that it will serve pages nearly as lightning-fast as static html pages. 
The server-side rendering also benefits those with slower devices, as the pages will render faster in the browser as no additional 
client-side processing is taking place. This helps providing a consequently similar experience on all devices. 

Because of this history, sites using MDWiki can use Up! in most cases as drop-in replacement, special care was given that both 
are mostly compatible. See the [FAQ](docs/faq.md#mdwiki-compatibility) for compatibility details.


## Credits

Up! is created by Dimitrios Karvounaris. 

Thank you to all that inspired and made Up! possible:

  * [MDWiki](http://dynalon.github.io/mdwiki/) - *Inspired* by this software
  * [Bootswatch](http://www.bootswatch.com/) - Provides *theme support* for Up!
  * [cebe/markdown](http://markdown.cebe.cc/) - super *fast*, highly *extensible* markdown parser


## License

Up! is licensed under the terms of the [GNU AGPLv3](https://www.gnu.org/licenses/agpl-3.0.html) with [additional terms and linking exceptions](LICENSE.txt). 

A commercial license is available upon request without some of these restrictions. Contact the author for details. 
