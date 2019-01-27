const mix = require('laravel-mix');
//const WebpackMonitor = require('webpack-monitor');

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

mix.js('resources/assets/js/app.js', 'public/js');
mix.sass('resources/assets/sass/app.scss', 'public/css')
    .browserSync('dm.flagstudio.loc');
mix.copyDirectory('resources/assets/img', 'public/images');

mix.version();

if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: 'source-map',
        resolve: {
            alias: {
                'jquery-ui/widget': 'blueimp-file-upload/js/vendor/jquery.ui.widget.js',
                'jquery-fileupload': 'blueimp-file-upload/js/vendor/jquery.fileupload.js'
            }
        }
    });
    mix.sourceMaps();
} else {
    mix.webpackConfig({
        resolve: {
            alias: {
                'jquery-ui/widget': 'blueimp-file-upload/js/vendor/jquery.ui.widget.js',
                'jquery-fileupload': 'blueimp-file-upload/js/vendor/jquery.fileupload.js'
            }
        },
        // plugins: [
        //     new WebpackMonitor({
        //         capture: true,
        //         launch: true,
        //     })
        // ]
    });
}

