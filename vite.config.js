import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                // Ilhas React (montadas em [data-island=…] nos Blades)
                'resources/js/islands.jsx',
            ],
            refresh: true,
        }),
        react(),
    ],
});
