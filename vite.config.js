import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'


export default defineConfig({
    plugins: [
        vue(),
        laravel([
            'resources/js/assets/app.css',
            'resources/js/app.js',
        ]),
    ],

    resolve: {
        alias: {
            '/assets': '/public/assets',
            'assets': '/public/assets',
        },
    },
});
