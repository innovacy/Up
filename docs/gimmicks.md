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


## Youtube videos
(coming next)
