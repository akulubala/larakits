const { mix } = require('laravel-mix');
mix.js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/admin.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .less('resources/assets/less/admin-lte.less','public/css/admin-lte.css')
   .combine([
       'node_modules/admin-lte/bootstrap/css/bootstrap.css',
       'node_modules/admin-lte/dist/css/skins/_all-skins.css',
       'node_modules/admin-lte/plugins/iCheck/square/blue.css',
       'node_modules/font-awesome/css/font-awesome.css',
       'node_modules/ionicons/dist/css/ionicons.css',
       'public/css/admin-lte.css',
   ], 'public/css/admin.css')
   //VENDOR RESOURCES
   .copy('node_modules/font-awesome/fonts/*.*','public/fonts/')
   .copy('node_modules/ionicons/dist/fonts/*.*','public/fonts/')
   .copy('node_modules/admin-lte/bootstrap/fonts/*.*','public/fonts/bootstrap')
   .copy('node_modules/admin-lte/dist/css/skins/*.*','public/css/skins')
   .copy('node_modules/admin-lte/dist/img','public/img')
   .copy('node_modules/admin-lte/plugins','public/plugins')
   .copy('node_modules/admin-lte/plugins/iCheck/square/blue.png','public/css')
   .copy('node_modules/admin-lte/plugins/iCheck/square/blue@2x.png','public/css');
