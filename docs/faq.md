# FAQ

## Some inline elements are not parsed in my installation. Why?

This can occur if you are using PHP's Opcode cache and PHPDocs are removed from source code. 

There is a setting in the php.ini, which should be left at the default `opcache.save_comments=1` and not be turned off.


## Is caching support planned?

No. Up! itself is already extremely light-weight and lightning-fast, even if it parses the files every time. 

Caching would only make sense, if there would be a significant performance benefit to gain, 
but additional cache checks, and resource usage by more classes would make any such small benefit forfeit.

The most processing time in Up! occurs from parsing the documents, however the integrated MarkDown parser is already extremely fast. 
The benchmarks at https://travis-ci.org/kzykhys/Markbench/jobs/19502391 show that: 
  * 1000 "empty document" parses take 49ms, that's less of 1ms per run.
  * 1000 average document parses require 5335ms, that is just 5.3ms per parsing with memory usage below 1MB.

There's currently nothing to gain from a cache than only adding more complexity. 


## Compatibility with mdwiki

Some of the common configuration settings with mdwiki are not necessarily acting 100% the same, 
however this has no side-effects or drawbacks at all. 




// TODO: below here

## FAQ


### Can I use Up! as replacement for mdwiki?

You can. My intention creating Up! was not to replace mdwiki. I'm still using both, they are just useful for different
cases, where I however need the same content. All I do is to remove the index.html from something I prepared for mdwiki
and I have the same content served by Up!, just with the benefit that it can be fully crawled by search engines and
some additional options.

### How compatible is Up! with mdwiki?

Up! is working very well as a drop-in replacement for mdwiki, as it is mostly compatible. So if you use Up! instead of
mdwiki with the same files, you should get a very similar output. The features not supported are gimmicks (read below).

### Which mdwiki gimmicks are supported?

All but two gimmicks are currently unsupported. The only gimmicks supported are alerts and themes.

It's unsure, if more gimmicks will be supported in future. If demand is high or someone else wants to implement
them, they might be supported at some time in future.

Unsupported gimmick features with the `[gimmick:feature]` syntax are currently completely removed from output
to simply showing something like `gimmick:feature` with a broken link.

