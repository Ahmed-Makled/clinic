import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: true,
    },
    plugins: [
        laravel({
            input: [
                // Main app assets
                'resources/css/bootstrap.min.css',
                'resources/css/app.css',
                'resources/js/app.js',

                // Admin module assets
                'Modules/Admin/Resources/assets/sass/app.scss',
                'Modules/Admin/Resources/assets/js/app.js',

                // Doctors module assets
                'Modules/Doctors/Resources/assets/sass/app.scss',
                'Modules/Doctors/Resources/assets/js/app.js',

                // User module assets
                'Modules/User/Resources/assets/sass/app.scss',
                'Modules/User/Resources/assets/js/app.js'
            ],
            refresh: true,
            buildDirectory: 'build'
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            '@admin': '/Modules/Admin/Resources/assets/js',
            '@doctors': '/Modules/Doctors/Resources/assets/js',
            '@user': '/Modules/User/Resources/assets/js'
        }
    }
});
