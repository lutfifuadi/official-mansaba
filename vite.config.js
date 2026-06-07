import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // ── CSS ──
                'resources/css/app.css',
                'resources/assets/css/mansaba-public.css',
                'resources/assets/css/demo.css',

                // ── Fonts ──
                'resources/assets/vendor/fonts/product-sans/product-sans.css',
                'resources/assets/vendor/fonts/trajan-pro/trajan-pro.css',
                'resources/assets/vendor/fonts/amiri/amiri.css',
                'resources/assets/vendor/fonts/iconify/iconify.css',

                // ── Vendor SCSS (always loaded) ──
                'resources/assets/vendor/scss/core.scss',
                'resources/assets/vendor/scss/pages/page-auth.scss',
                'resources/assets/vendor/scss/pages/page-misc.scss',
                'resources/assets/vendor/scss/pages/front-page.scss',
                'resources/assets/vendor/scss/pages/cards-advance.scss',

                // ─── Vendor Libs CSS ──
                'resources/assets/vendor/libs/@form-validation/form-validation.scss',
                'resources/assets/vendor/libs/node-waves/node-waves.scss',
                'resources/assets/vendor/libs/pickr/pickr-themes.scss',
                'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss',
                'resources/assets/vendor/libs/typeahead-js/typeahead.scss',
                'resources/assets/vendor/libs/swiper/swiper.scss',
                'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
                'resources/assets/vendor/libs/quill/editor.scss',
                'resources/assets/vendor/libs/apex-charts/apex-charts.scss',

                // ── JS: App ──
                'resources/js/app.js',

                // ── JS: Theme Core ──
                'resources/assets/js/main.js',
                'resources/assets/js/front-main.js',
                'resources/assets/js/config.js',
                'resources/assets/js/front-config.js',
                'resources/assets/js/pages-auth.js',
                'resources/assets/js/dashboards-operator.js',
                'resources/assets/js/dashboards-analytics.js',

                // ── JS: Vendor (always loaded) ──
                'resources/assets/vendor/js/helpers.js',
                'resources/assets/vendor/js/template-customizer.js',
                'resources/assets/vendor/js/menu.js',
                'resources/assets/vendor/js/bootstrap.js',
                'resources/assets/vendor/js/dropdown-hover.js',
                'resources/assets/vendor/js/mega-dropdown.js',

                // ── JS: Vendor Libs ──
                'resources/assets/vendor/libs/jquery/jquery.js',
                'resources/assets/vendor/libs/popper/popper.js',
                'resources/assets/vendor/libs/node-waves/node-waves.js',
                'resources/assets/vendor/libs/pickr/pickr.js',
                'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
                'resources/assets/vendor/libs/hammer/hammer.js',
                'resources/assets/vendor/libs/@form-validation/popular.js',
                'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
                'resources/assets/vendor/libs/@form-validation/auto-focus.js',
                'resources/assets/vendor/libs/swiper/swiper.js',
                'resources/assets/vendor/libs/quill/quill.js',
                'resources/assets/vendor/libs/apex-charts/apexcharts.js',
                'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        chunkSizeWarningLimit: 600,
    },
});
