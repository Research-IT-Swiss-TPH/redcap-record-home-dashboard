# Record Home Dashboard
A REDCap External Module that enables to add a customized Dashboard to the Record Homepage


## Setup

Install the module from REDCap module repository and enable over Control Center.


## Psalm

Run Psalm Taint Analysis to check for vulnerabilites:

```bash
   ./vendor/bin/psalm --no-diff --taint-analysis
``` 

`--no-diff` is practical if you run Psalm multiple times without differences in the file.
Read more about Psalm in the official [Psalm Manual](https://psalm.dev/docs/).

## Developer Notice

Using laravel-mix for Vue.js development: `npm run dev` => `npx mix watch`
[Laravel Mix Docs](https://laravel-mix.com/docs/6.0/api)

## Changelog

Version | Description
------- | --------------------
v1.0.0  | Initial release.
