CHANGELOG
=========

Version 2.0.0
-------------

This version tends to improve first of all to get started using Up!, making 
it as easy as it was always intended to be.

  * Easy installation also without composer
  * Completely revamped and improved documentation (wip)
  * Youtube links can be rendered as Youtube embedded videos
  * New default theme that is shipped with Up! (planned)
  * New extension system for implicit/explicit gimmicks (wip)
  * Experimental extension system and documentation for custom gimmicks (wip)
  * Mobile optimizations: App-style menu (planned)
  * New `test.md`
    * ... provides examples for all syntax
    * ... assists testing proper rendering of all syntax
    * ... to test proper degradation or removal of all non-supported mdwiki-gimmicks
  * Documentation itself is an Up! wiki, can be browsed after installing Up!
  * Documentation itself serves as example implementation
  * Example config.json provided (wip)
  * Example navigation file provided
  * A generic title can be set/appended for all documents
  * Default "File not found" message when no 404.md exists is now fully rendered like a 404.md instead of dying with an one-liner
  * 404.md no longer part of the repository, so it can be created without conflicting with 'git pull' updates
  * Proper support of mdwiki-style links officially dropped and won't be implemented
  * Introducing Inversion of Control, code refactoring

Version 1.0.1
-------------

This will be the last v1. Some further versions were planned, but they became
finally part of the v2, after deciding to refactor some things and implement 
gimmicks as a kind of plugins. 
