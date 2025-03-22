import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        // host: '192.168.1.35',
        port: 5173,
        strictPort: true,
        cors: true,
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/loader.js',
                'resources/css/landingpage.css',
                'resources/css/teamdetails.css',
                'resources/js/teamdetails.js',
                'resources/images/05.webp',
                'resources/images/palaroBkg.jpg',
                'resources/images/svg-bkg.svg',
                'resources/images/palaroLogo.png',
                'resources/images/palaroCleanLogo.png',
                'resources/images/landingPageBkg.jpg',
                'resources/images/feather.png',
                'resources/images/default.png',
                'resources/images/bg-pattern.svg',
                'resources/css/icons.min.css',
                'resources/js/pages/simple-datatables.init.js',
                'resources/js/pages/crm-dashboard.init.js',
                'resources/scss/app.scss',
                'resources/scss/icons.scss',
                'resources/css/layout.css',
                'resources/css/override.css',
                'resources/js/pages/datatable.init.js',
                'resources/js/pdf-export.js'
            ],
            refresh: true,
        }),
    ],
});
