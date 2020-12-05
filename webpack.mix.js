const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .copy('resources/js/static/dropzone.min.js', 'public/js/static')
    .copy('resources/js/static/sortable.min.js', 'public/js/static')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/auth/login.scss', 'public/css/auth')
    .sass('resources/sass/auth/base.scss', 'public/css/auth')
    .sass('resources/sass/permissions/base.scss', 'public/css/permissions')
    .sass('resources/sass/users/base.scss', 'public/css/users')
    .sass('resources/sass/contents/base.scss', 'public/css/contents')
    .sass('resources/sass/ce/datatable.scss', 'public/css/ce')
    .sass('resources/sass/ce/sortable.scss', 'public/css/ce')
    .sass('resources/sass/ce/dropzone.scss', 'public/css/ce');

mix.copyDirectory('node_modules/tinymce/icons', 'public/plugins/tinymce/icons');
mix.copyDirectory('node_modules/tinymce/plugins', 'public/plugins/tinymce/plugins');
mix.copyDirectory('node_modules/tinymce/skins', 'public/plugins/tinymce/skins');
mix.copyDirectory('node_modules/tinymce/themes', 'public/plugins/tinymce/themes');
mix.copy('node_modules/tinymce/tinymce.min.js', 'public/plugins/tinymce/tinymce.min.js');
