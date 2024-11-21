import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/styles.css',
                'resources/css/dark.css',
                'resources/css/template.css',
                'resources/js/app.js',
                'resources/js/index.js',
                'resources/js/template.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
          external: ['jquery', 'jquery.easing'],
        },
      },
    
});
