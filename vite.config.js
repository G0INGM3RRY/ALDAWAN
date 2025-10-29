import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/welcome.css', 
                'resources/css/dashboard.css', 
                'resources/css/bootstrap-standards.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
