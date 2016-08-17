## Individual navigations

You have the feature to create separate navigations for subfolders. By adding a navigation.md in the specific subfolder,
Up! will create a different navigation for all files in this subfolder and all subfolders.

This is optional though. If Up! can't find a navigation.md in the folder the md file exists, it will search the parent folders until it finds
one and use it.


## Multiple configuration


If you have structured your documents into many folders, you can place a configuration file into any of them 
and Up! will alternatively use the individual settings of this one for all documents within that folder and it's subfolders. 

Up! will look up first though in the main folder where you have installed Up! for a `config.json`
The config.json

// TODO: Refer to advanced features documentation for creating multiple sections of a wiki
