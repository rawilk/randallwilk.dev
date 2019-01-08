const mix = require('laravel-mix');
require('laravel-mix-auto-extract');

mix.setPublicPath('public');

mix
    .sass('resources/scss/vendor/vendor-frontend.scss', 'css/vendor')
    .sass('resources/scss/frontend/theme/theme.scss', 'css/frontend/theme')
    .sass('resources/scss/frontend/theme/theme-elements.scss', 'css/frontend/theme')
    .sass('resources/scss/frontend/theme/skins/default.scss', 'css/frontend/theme/skins')
    .sass('resources/scss/frontend/app.scss', 'css/frontend')

    .js('resources/js/core/core.js', 'js/core')
    .js('resources/js/frontend/app.js', 'js/frontend')
    .js('resources/js/frontend/pages/contact/index.js', 'js/frontend/pages/contact')
    .js('resources/js/frontend/pages/projects/index.js', 'js/frontend/pages/projects');

if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
}

mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.json', '.vue'],
        alias: {
            '~': path.join(__dirname + '/resources/js')
        }
    }
});

// Auto extract vendor libraries
mix.autoExtract();