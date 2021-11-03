let mix = require('laravel-mix');

mix
.sass('src/style.scss', 'dist')
.js('src/Editor/appEditor.js', 'dist')
.vue();