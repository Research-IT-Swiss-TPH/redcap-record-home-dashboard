# Record Home Dashboard
A REDCap External Module that enables to add a customized Dashboard to the Record Homepage

![Demo](record_home_dashboard_demo.gif)


## Setup

Install the module from REDCap module repository and enable over Control Center.

## Usage

Please read the [Documentation](https://tertek.github.io/redcap-record-home-dashboard/).

## Developer Notice

### Psalm

Run Psalm Taint Analysis to check for vulnerabilites:

```bash
   ./vendor/bin/psalm --no-diff --taint-analysis
``` 

`--no-diff` is practical if you run Psalm multiple times without differences in the file.
Read more about Psalm in the official [Psalm Manual](https://psalm.dev/docs/).

### Vue

Using laravel-mix for Vue.js development: `npm run dev` => `npx mix watch`
[Laravel Mix Docs](https://laravel-mix.com/docs/6.0/api)


