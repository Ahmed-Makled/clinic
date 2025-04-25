import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: 'manifest.json',
    },
    plugins: [
        laravel({
            input: [
                // Main app assets
                'resources/css/bootstrap.min.css',
                'resources/css/app.css',
                'resources/js/app.js',

                // Module assets
                'Modules/Dashboard/Resources/assets/css/app.css',
                'Modules/Dashboard/Resources/assets/js/app.js',
                'Modules/Doctors/Resources/assets/css/app.css',
                'Modules/Doctors/Resources/assets/js/app.js',
                'Modules/Auth/Resources/assets/css/app.css',
                'Modules/Auth/Resources/assets/js/app.js',
                'Modules/Users/Resources/assets/css/app.css',
                'Modules/Users/Resources/assets/js/app.js',
                'Modules/Appointments/Resources/assets/css/app.css',
                'Modules/Appointments/Resources/assets/js/app.js',
                'Modules/Specialties/Resources/assets/css/app.css',
                'Modules/Specialties/Resources/assets/js/app.js'
            ],
            refresh: true,
            buildDirectory: 'build'
        }),

        tailwindcss(),

    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            '@dashboard': '/Modules/Dashboard/Resources/assets/js',
            '@doctors': '/Modules/Doctors/Resources/assets/js',
            '@auth': '/Modules/Auth/Resources/assets/js',
            '@users': '/Modules/Users/Resources/assets/js',
            '@appointments': '/Modules/Appointments/Resources/assets/js',
            '@specialties': '/Modules/Specialties/Resources/assets/js'
        }
    }
});
