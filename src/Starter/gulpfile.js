var elixir = require('laravel-elixir');
elixir(function(mix) {
    mix.styles([
        '../bower_components/AdminLTE/bootstrap/css/bootstrap.min.css',
        'font-awesome.css',
        'ionicons.min.css',
        '../bower_components/AdminLTE/dist/css/AdminLTE.min.css',
        '../bower_components/AdminLTE/dist/css/skins/_all-skins.min.css',
        '../bower_components/AdminLTE/plugins/iCheck/square/blue.css',
        '../bower_components/AdminLTE/plugins/morris/morris.css',
        '../bower_components/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.css',
        '../bower_components/AdminLTE/plugins/datepicker/datepicker3.css',
        '../bower_components/AdminLTE/plugins/select2/select2.min.css',
        '../bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css',
        '../bower_components/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
    ]);
});

elixir(function(mix) {
    mix.scripts([
        '../bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js',
        '../bower_components/AdminLTE/plugins/jQueryUI/jquery-ui.min.js',
        'no_conflicts.js',
        '../bower_components/AdminLTE/bootstrap/js/bootstrap.min.js',
        '../bower_components/raphael/raphael.min.js',
        '../bower_components/AdminLTE/plugins/morris/morris.min.js',
        '../bower_components/AdminLTE/plugins/sparkline/jquery.sparkline.min.js',
        '../bower_components/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        '../bower_components/AdminLTE/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        '../bower_components/AdminLTE/plugins/knob/jquery.knob.js',
        '../bower_components/moment/min/moment.min.js',
        '../bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js',
        '../bower_components/AdminLTE/plugins/select2/select2.min.js',
        '../bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js',
        '../bower_components/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
        '../bower_components/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js',
        '../bower_components/AdminLTE/plugins/fastclick/fastclick.js',
        '../bower_components/AdminLTE/plugins/iCheck/icheck.min.js',
        '../bower_components/AdminLTE/dist/js/app.min.js',
        'app.js'
    ]);
});

elixir(function(mix){
    mix.copy('resources/assets/bower_components/AdminLTE/bootstrap/fonts', 'public/build/fonts');
    mix.copy('resources/assets/fonts', 'public/build/fonts');
    mix.copy('resources/assets/bower_components/AdminLTE/dist/img', 'public/admin/dist/img');
    mix.copy('resources/assets/bower_components/AdminLTE/plugins/iCheck/square/*.png', 'public/build/css');
    mix.version(['css/all.css', 'js/all.js']);
});