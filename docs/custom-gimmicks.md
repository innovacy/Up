# Extending with custom Gimmicks

From version 2.0 onward Up! can be extended with custom gimmicks. Unlike the rest of Up!, 
this is only for advanced users and developers and will require you to write PHP code.

Using custom gimmicks will allow you to extend the MarkDown syntax with custom non-standard MarkDown features.

Warning: This is an experimental feature and specifications may change in future. Yet it was documented, 
to allow for ideas to be implemented and see how it can be improved in future. 

You should check the existing gimmick classes for example code, as some things are not further explained here.


## Types of gimmicks

There are two types of extension: explicit and implicit. 

Whenever possible, you should prefer explicit extensions over implicit ones. 

Explicit gimmicks have priority over implicit ones. If an explicit matches, no other gimmick will 
be asked to parse the MarkDown. 


### Explicit extensions

Explicit extensions use the keyword 'gimmick:' appended with a custom suffix to be identified. 
The extensions are only called if the specific suffix associated with an extension is found. 
These are always replaced with the output of the extension. 

If no gimmick class is found for an extension, then the 'gimmick:' occurence in the MarkDown
is removed or if possible downgraded.

Only one class can be registered for a gimmick suffix. 


### Implicit extensions

Implicit extensions have no keyword associated and work on specific contexts, like a link or paragraphs. 
When you want to parse generic MarkDown you must use an implicit extension. If this MarkDown is not replaced 
by the gimmick, it may automatically degrade to something readable (see alert gimmick for an example).

Several classes can be registered for each context, however they will be called only until one matches.

Implicit extensions don't further specify what they are looking for, only the type they are looking 
for, like links or paragraphs. As that, all gimmicks are called in order and given the chance to recognize
themselves if what they are looking for is there. 

In turn, the extension must return either the parsed output with which the original MarkDown 
should be replaced with or signalize to Up!, that they did not process anything (see details further below).


### Parsing of content

The extension must make sure, that the original MarkDown is further parsed by calling the Up! parser, 
as example in case of a paragraph which is a block element, to further parse it's inline elements. 

Your extension may decide to not call the parser for further parsing, especially on inline elements like links. 
However, keep in mind, that inline elements can contain other inline elements. Your extension may further parse 
only some of the MarkDown, as example the original MarkDown without your output or parse it before applying your changes. 

If you do not further call the parser, the chain of parsing will stop and your extension 
will be responsible that some MarkDown may not be correctly displayed. 


## Create a new gimmick extension

Extensions are classes of their own. They must extend from the class \Innovacy\Up\Gimmick\GimmickBase.

Currently extensions must be placed in src/Innovacy/Up/Gimmick and can exist only under the namespace Innovacy\Up\Gimmick. 

Note: This will be changed in a future version, to have a custom folder and support custom namespaces 
for custom gimmicks not shipped with Up!. Please remember, that this feature for developing custom gimmicks 
is considered experimental and anything may change, why this separate location for custom gimmicks is not yet implemented. 

Each extension must set some values of the predefined properties and implement one specific
public method related to the specific MarkDown context it parses.


## Methods of a gimmick extension

Each gimmick must implement exactly one of the following methods. If more than one is implemented, 
there will be still only one registered, but it's not given which one. So never implement more than 
one of the following methods in one gimmick class. 

You don't need to specify other methods or properties required from other methods than just the one implemented. 


### renderLink()

This method hooks into link inline elements. 

The `$isLinkGimmick` property must be set to true:

    public $isLinkGimmick = true;

To use the explicit gimmick link syntax like this:

    [gimmick:myextension](content-for-gimmick)

you must also specify your suffix for the 'gimmick:' keyword in your class:

    public $gimmickKeyword = 'myextension';

To create an implicit extension, leave this property empty or don't specify it at all.


### renderParagraph()

This method hooks into paragraphs. 

The `$isParagraphGimmick` property must be set to true:

    public $isParagraphGimmick = true;

Currently only implicit extensions are possible for paragraphs. 


## Return values of a gimmick extension

An extension can return three values: `false`, `true` or a string.

When an extension returns a string, it becomes part of the rendered content. No further 
parsing is done by Up!, as this is the responsibility of the extension, to continue the parsing 
by calling Up!'s parser on the part that should be parsed, before returning it. 

If an extension returns `false`, it signalizes Up! to stop parsing it further. Up! understands 
this (especially in the case of implicit gimmicks) as the gimmick been suitable for the content, 
however as not having returned any content for output (the extension may have a different purpose), 
so it will in this case always remove the gimmick-MarkDown and output nothing, bypassing any 
further processing. 

If an extension returns `true`, it signalizes Up! to continue processing. In the case of implicit ones, 
it will call the next registered gimmick (if any) or in case of explicit gimmicks it may simply remove 
the content or in some cases downgrade the content to something that can be still displayed without 
the reference to the gimmick.

If no gimmick fits or is registered at all, but an explicit gimmick reference is found, Up! will do 
the same as if an explicit gimmick returned `true`, such maximizing compatibility with different installations 
and even mdwiki (for those who switched from it).
