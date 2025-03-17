import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        // host: '192.168.0.146',
        port: 5173,
        strictPort: true,
        cors: true,
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/landingpage.css',
                'resources/css/teamdetails.css',
                'resources/js/teamdetails.js'
            ],
            refresh: true,
        }),
    ],
});
