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
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/auth/login.scss', 'public/css/auth')
    .sass('resources/sass/auth/base.scss', 'public/css/auth')
    .sass('resources/sass/permissions/base.scss', 'public/css/permissions')
    .sass('resources/sass/users/base.scss', 'public/css/users')
    .sass('resources/sass/ce/datatable.scss', 'public/css/ce')
    .sass('resources/sass/ce/sortable.scss', 'public/css/ce')
    .sass('resources/sass/ce/dropzone.scss', 'public/css/ce');
