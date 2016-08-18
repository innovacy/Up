# Configuration: config.json Reference

Up! will work with defaults without any further configuration. However, Up! allows you 
to modify some behaviours or set your own preferences by adding a configuration file `config.json`.

Start by creating the configuration file in the main folder of your documents, where you have installed Up!. 
The file should start with a `{` and end with a `}`. Between these can be placed any of the settings.  The settings
usually take the format:

    "KEY": "your value"

where KEY should not be modified and the content after `:` can be changed. If a setting is shown in quotes, then it has to 
be written like this and the quotes should not be removed. 

Each setting should be on it's own line and separated with a comma from the next one. All of the settings are optional. 

Misspelled or unknown settings cause no error, but are simply ignored. If one of your settings seems to be ignored, check for typos. 

Hint: Learn how you can overwrite settings for sections of your site in [Advanced features](multiple-navigation.md#Multiple_configuration)


## title

    "title": "Your individual generic title"

With this setting you define a generic title for your site. 

Your title will be set for all documents that have no individual title (see [Setting document title in browser](getting-started.md#Setting_document_title_in_browser)) 
and appended with a separator to all documents that have one. 


