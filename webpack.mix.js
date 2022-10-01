const mix = require('laravel-mix');

mix.setPublicPath('./web');

mix.js(['resources/js/app.jsx'], 'assets');