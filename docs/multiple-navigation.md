// TODO below here

## Individual navigations

You have the feature to create separate navigations for subfolders. By adding a navigation.md in the specific subfolder,
Up! will create a different navigation for all files in this subfolder and all subfolders.

This is optional though. If Up! can't find a navigation.md in the folder the md file exists, it will search the parent folders until it finds
one and use it.


## Multiple 404

See [File not found (404)](advanced-features.md#404_File_not_found) for basic usage.

...searched recursively from current to all parent directories, first found used...


## Multiple configuration


If you have structured your documents into many folders, you can place a configuration file into any of them 
and Up! will alternatively use the individual settings of this one for all documents within that folder and it's subfolders. 

Up! will look up first though in the main folder where you have installed Up! for a `config.json`
The config.json

// TODO: Refer to advanced features documentation for creating multiple sections of a wiki


### Changing settings with config.json

You can have a config.json file in the main folder and in any of the subdirectories.

Up! looks first in the main folder for config.json file and will load it. Then it will search resursively beginning
with requested path all parents folders for a config.json. The first file that will be found, will be loaded and can
overwrite settings of the main config.json file. Only one additional config.json will be loaded, other files further up
in the folder structure will be ignored.

