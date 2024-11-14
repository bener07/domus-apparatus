let mix = require('laravel-mix');
// import mix from 'laravel-mix';

mix.js('resources/js/app.js', 'public/js')
.js('resources/js/bootstrap.js', 'public/js')
.js('resources/js/products.js', 'public/js')
.js('resources/js/dashboard.js', 'public/js')
.js('resources/js/scripts.js', 'public/css');