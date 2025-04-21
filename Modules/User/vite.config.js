import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        outDir: '../../public/build-user',
    },
    plugins: [
        laravel({
            input: [
                'Modules/User/Resources/assets/sass/app.scss',
                'Modules/User/Resources/assets/js/app.js'
            ],
            refresh: true,
            buildDirectory: 'build-user'
        }),
    ],
});
