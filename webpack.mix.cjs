let mix = require('laravel-mix');
// import mix from 'laravel-mix';

mix.webpackConfig({
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                MIX_PUSHER_APP_KEY: JSON.stringify(process.env.MIX_PUSHER_APP_KEY),
                MIX_PUSHER_APP_CLUSTER: JSON.stringify(process.env.MIX_PUSHER_APP_CLUSTER),
            },
        }),
    ],
}).js('resources/js/app.js', 'public/js')
.js('resources/js/bootstrap.js', 'public/js')
.js('resources/js/products.js', 'public/js')
.js('resources/js/dashboard.js', 'public/js')
.js('resources/js/scripts.js', 'public/css');