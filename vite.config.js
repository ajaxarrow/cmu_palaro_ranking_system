import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',  // Allow connections from any IP
        port: 5173,       // Use the standard Vite port (different from Nginx port 9000)
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