const mix = require('laravel-mix');

mix.setPublicPath('./');

mix.js(['resources/js/app.jsx'], 'web/assets');
mix.js(['modules/penpen/resources/app.jsx'], 'modules/penpen/resources/dist');