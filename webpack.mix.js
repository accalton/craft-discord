const mix = require('laravel-mix');
const Dotenv = require('dotenv-webpack');

mix.setPublicPath('./');

mix.js(['resources/js/app.jsx'], 'web/assets');
mix.js(['modules/penpen/resources/app.jsx'], 'modules/penpen/resources/dist');

mix.webpackConfig({
    plugins: [
    new Dotenv({
        path: `${__dirname}/.env`,
        allowEmptyValues: true,
    })],
});
