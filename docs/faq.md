
## FAQ

### Can I use Up! as replacement for mdwiki?

You can. My intention creating Up! was not to replace mdwiki. I'm still using both, they are just useful for different
cases, where I however need the same content. All I do is to remove the index.html from something I prepared for mdwiki
and I have the same content served by Up!, just with the benefit that it can be fully crawled by search engines and
some additional options.

### How compatible is Up! with mdwiki?

Up! is working very well as a drop-in replacement for mdwiki, as it is mostly compatible. So if you use Up! instead of
mdwiki with the same files, you should get a very similar output. The features not supported are gimmicks (read below).

### What mdwiki gimmicks are supported?

All but two gimmicks are currently unsupported. The only gimmicks supported are alerts and themes.

It's unsure, if more gimmicks will be supported in future. If demand is high or someone else wants to implement
them, they might be supported at some time in future.

Unsupported gimmick features with the `[gimmick:feature]` syntax are currently completely removed from output
to simply showing something like `gimmick:feature` with a broken link.

