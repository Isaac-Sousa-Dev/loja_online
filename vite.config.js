import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/partner/product-create-wizard.js',
                'resources/js/partner/product-edit-wizard.js',
            ],
            refresh: true,
        }),
    ],
    server: { // <--- ADICIONE ESTE BLOCO
        host: '0.0.0.0',
        watch: {
            usePolling: true,
        },
        hmr: {
            host: 'localhost',
        },
    },
});
