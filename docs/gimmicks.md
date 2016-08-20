# Gimmicks

## Selecting a theme

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



## Alert gimmick

Whenever a paragraph starts with a special trigger word that is followed by a colon `:` or exclamation mark `!`,
they are rendered as alert boxes.

These trigger words are case insensitive and are:

Type       | Trigger
-----------|---------
Warning    |warning, achtung, attention, warnung, atención, guarda, advertimiento
Note       |note, beachte
Hint       |hint, tip, tipp, hinweis

**Example:**

    Hint: This is an alert paragraph. 

**Preview:**

Hint: This is an alert paragraph. 

## Youtube videos

Whenever you insert an youtube link without a caption, the Youtube gimmick will recognize it and embed 
the video directly. 

Recognized are youtube.com and youtu.be links. It does not matter if a protocol is used or it has additional
parameters. It should recognize all such links including additional parameters, which it will append to 
the embedded video. 

If the link caption is not empty, it will render simply as a regular link.

**Example:**

    [](https://youtu.be/Lc-vINJmhNk?t=1)

**Preview:**

[](https://youtu.be/Lc-vINJmhNk?t=1)
