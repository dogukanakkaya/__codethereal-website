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

// Admin mix
mix.js('resources/js/app.js', 'public/js')

    // Static
    .copy('resources/js/static/dropzone.min.js', 'public/js/static')
    .copy('resources/js/static/sortable.min.js', 'public/js/static')

    // Sass
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/auth/login.scss', 'public/css/auth')
    .sass('resources/sass/auth/base.scss', 'public/css/auth')
    .sass('resources/sass/ce/datatable.scss', 'public/css/ce')
    .sass('resources/sass/ce/sortable.scss', 'public/css/ce')
    .sass('resources/sass/ce/dropzone.scss', 'public/css/ce')
    .sass('resources/sass/ce/select2.scss', 'public/css/ce')

// Copy directories
mix.copyDirectory('resources/img', 'public/img')
    .copyDirectory('resources/fonts', 'public/fonts');

// Copy node modules
mix.copyDirectory('node_modules/tinymce/icons', 'public/plugins/tinymce/icons')
    .copyDirectory('node_modules/tinymce/plugins', 'public/plugins/tinymce/plugins')
    .copyDirectory('node_modules/tinymce/skins', 'public/plugins/tinymce/skins')
    .copyDirectory('node_modules/tinymce/themes', 'public/plugins/tinymce/themes')
    .copy('node_modules/tinymce/tinymce.min.js', 'public/plugins/tinymce/tinymce.min.js')
    .copy('node_modules/codethereal-iconpicker/dist/iconpicker.js', 'public/plugins/iconpicker');


// Site mix
const sitePrefix = 'site'
mix.js('resources/site/js/app.js', `public/${sitePrefix}/js`)
    .vue();

mix
    .sass('resources/site/sass/style.scss', `public/${sitePrefix}/css`)
    .copy('resources/site/js/prism.js', `public/${sitePrefix}/js`)
    .copy('resources/site/css/prism.css', `public/${sitePrefix}/css`)
    .copyDirectory('resources/site/img', `public/${sitePrefix}/img`)
    .copyDirectory('node_modules/bootstrap-icons/font', `public/${sitePrefix}/bootstrap-icons`)

if (mix.inProduction()) {
    mix.version();
}
