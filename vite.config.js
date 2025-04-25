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
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/tables.css',
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
            '@specialties': '/Modules/Specialties/Resources/assets/js',
            '$': 'jQuery'
        }
    },
    optimizeDeps: {
        include: ['jquery', 'select2']
    }
});
