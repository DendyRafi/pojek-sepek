import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/welcome.css',
                'resources/css/pengaturan.css',
                'resources/css/confirm-modal.css',
                'resources/js/app.js',
                'resources/js/welcome.js',
                'resources/js/pengaturan.js',
                'resources/js/custom-bg-page.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
