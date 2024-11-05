[![](https://img.shields.io/packagist/v/inspiredminds/contao-pdf-generation.svg)](https://packagist.org/packages/inspiredminds/contao-pdf-generation)
[![](https://img.shields.io/packagist/dt/inspiredminds/contao-pdf-generation.svg)](https://packagist.org/packages/inspiredminds/contao-pdf-generation)

Contao Extended Cache Controls
==============================

This adds more settings to the _Cache settings_ of a Contao page. These settings can be useful for large sites to keep
the HTTP cache size smaller by only caching the most relevant URLs (e.g. only canonical URLs, only URLs with no query 
parameters or only certain parameters etc.).

* __Do not cache non-canonicals__: Prevents caching if canonical URLs are enabled for this page and the current URL does
not match the canonical URL.
* __Do not cache query parameters__: Prevents caching if query parameters are present in the URL. 
* __Allowed query parameters__: Allows you to define allowed query parameters for the previous setting. If the URL 
contains a query parameter not present in this comma-separated list, it will not be cached.
* __Do not cache fragment parameters__: Prevents caching if fragment parameters are present in the URL, e.g. 
`/page-alias/foo/bar`
* __Allowed fragment parameters__: Allows you to define allowed fragment parameters for the previous setting. If the URL 
contains a fragment parameter not present in this comma-separated list, it will not be cached.
