import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/template.css',
                'resources/js/bootstrap.js',
                'resources/js/app.js',
                'resources/js/index.js',
                'resources/js/template.js',
                'resources/js/dashboard/users.js',
                'resources/js/dashboard/products.js',
                'resources/js/dashboard/classrooms.js',
                'resources/js/dashboard/disciplines.js',
                'resources/js/user/requisitar.js',
                'resources/js/user/checkout.js',
                'resources/js/user/entregar.js',
		        'resources/js/app_dashboard.js',
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
