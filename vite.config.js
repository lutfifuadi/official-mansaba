import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/assets/js/main.js',
                'resources/assets/js/dashboards-operator.js',
                'resources/assets/js/dashboards-analytics.js',
                'resources/assets/css/mansaba-public.css',
                'resources/assets/vendor/fonts/product-sans/product-sans.css',
                'resources/assets/vendor/fonts/trajan-pro/trajan-pro.css',
                'resources/assets/vendor/fonts/amiri/amiri.css',
                'resources/assets/vendor/scss/core.scss',
            ],
            refresh: true,
        }),
    ],
    build: {
        chunkSizeWarningLimit: 600,
    },
});
