import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import babel from 'vite-plugin-babel';

export default defineConfig({
    plugins: [
        babel(),

        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/front.css',
                'resources/css/docs.css',
                'resources/js/docs.js',

                // Filepond...
                'resources/js/filepond.js',
                'resources/css/vendor/filepond.css',
            ],

            refresh: true,

            valetTls: 'randallwilk.dev.test',
        }),
    ],
});
