const mix = require('laravel-mix')

mix.setPublicPath('public')
mix.sass('resources/assets/scss/app.scss', 'public/css/app.css')
mix.js('resources/assets/js/app.js', 'public/js/app.js')
