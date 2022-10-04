import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import babel from 'vite-plugin-babel';
import fs from 'fs';
import { homedir } from 'os';
import { resolve } from 'path';

let host = 'randallwilk.dev.test';

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
        }),
    ],

    server: detectServerConfig(host),
});

function detectServerConfig(host) {
    let keyPath = resolve(homedir(), `.config/valet/Certificates/${host}.key`);
    let certificatePath = resolve(homedir(), `.config/valet/Certificates/${host}.crt`);

    if (! fs.existsSync(keyPath)) {
        return {};
    }

    if (! fs.existsSync(certificatePath)) {
        return {};
    }

    return {
        hmr: { host },
        host,
        https: {
            key: fs.readFileSync(keyPath),
            cert: fs.readFileSync(certificatePath),
        }
    };
}
