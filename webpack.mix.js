let mix = require('laravel-mix');

mix
.sass('src/style.scss', 'dist')
.js('src/editor/appEditor.js', 'dist')
.js('src/Render/appRender.js', 'dist')
.sourceMaps()
.vue();
