import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0', 
        port: 5173,
        strictPort: true,
        cors: {
            origin: '*',  // Allow all origins
            methods: ['GET', 'POST', 'OPTIONS'],
            allowedHeaders: ['Content-Type', 'X-Requested-With'],
            credentials: true,
        },
        hmr: {
            host: 'localhost',
            protocol: 'ws',
        },
        watch: {
            usePolling: true,  // Better for Docker environments
        },
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