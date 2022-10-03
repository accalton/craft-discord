const mix = require('laravel-mix');

mix.setPublicPath('./');

mix.js(['resources/js/app.jsx'], 'web/assets');
mix.js(['modules/discordbot/resources/main.js'], 'modules/discordbot/resources/dist');