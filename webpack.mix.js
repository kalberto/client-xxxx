// noinspection JSAnnotator
let mix = require('laravel-mix');
let fs = require('fs');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .scripts(['node_modules/angular/angular.js', 'node_modules/ng-file-upload/dist/ng-file-upload.js'], 'public/js/vendor.js')
    .babel(['resources/assets/js/site/clientes/services/api.services.js'], 'public/js/services.js')
    .babel(['resources/assets/js/site/clientes/services/chamadas.services.js'], 'public/js/chamadas.js')
    .babel(['resources/assets/js/site/clientes/services/logins.services.js'], 'public/js/logins.js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/area-cliente.scss', 'public/css')
    .sass('resources/assets/sass/main.scss', 'public/css')
    .sass('resources/assets/sass/main-custom.scss', 'public/css')
    .sass('resources/assets/sass/produto.fastpack.scss', 'public/css')
    .sass('resources/assets/sass/comparativo.scss', 'public/css')
    .js('resources/assets/js/site/produto.fastpack.js', 'public/js/produto.fastpack.js')
    .js('resources/assets/js/site/comparativo.fastpack.js', 'public/js/comparativo.fastpack.js')
    .options({
        processCssUrls: false
    });

// from https://laracasts.com/discuss/channels/elixir/mix-all-js-in-directory
let getFiles = function (dir) {
    // get all 'files' in this directory
    // filter directories
    return fs.readdirSync(dir).filter(file => {
        return fs.statSync(`${dir}/${file}`).isFile();
    });
};
getFiles('resources/assets/js/site/clientes/controllers/').forEach(function (completeFileName) {
    fileName = completeFileName.split('.');
    mix.babel(['resources/assets/js/site/clientes/controllers/' + completeFileName], 'public/js/' + fileName[0] + '.' + fileName[2])
});