<?php

namespace Tks\Larakits;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Tks\Larakits\Kits\Crypto;
use Tks\Larakits\Kits\FormElementMaker;
use Tks\Larakits\Kits\FormMaker;

class LarakitsProvider extends ServiceProvider
{
    /**
     * Boot method
     * @return void
     */
    public function boot()
    {
        @mkdir(base_path('resources/larakits/crud'));
        $this->publishes([
            __DIR__.'/Templates' => base_path('resources/larakits/crud'),
            __DIR__.'/Starter/config/larakits.php' => base_path('config/larakits.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /*
        |--------------------------------------------------------------------------
        | Providers
        |--------------------------------------------------------------------------
        */

        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\AlfredoRamos\ParsedownExtra\ParsedownExtraServiceProvider::class);
        /*
        |--------------------------------------------------------------------------
        | Register the Kits
        |--------------------------------------------------------------------------
        */
        $this->app->singleton('FormMaker', function () {
            return new FormMaker();
        });
        $this->app->singleton('FormElementMaker', function () {
            return new FormElementMaker();
        });
        $this->app->singleton('Crypto', function () {
            return new Crypto();
        });
        $loader = AliasLoader::getInstance();
        $loader->alias('FormMaker', \Tks\Larakits\Facades\FormMaker::class);
        $loader->alias('FormElementMaker', \Tks\Larakits\Facades\InputMaker::class);
        $loader->alias('Crypto', \Tks\Larakits\Kits\Crypto::class);
        $loader->alias('Markdown', \AlfredoRamos\ParsedownExtra\Facades\ParsedownExtra::class);
        // Thrid party
        $loader->alias('Form', \Collective\Html\FormFacade::class);
        $loader->alias('HTML', \Collective\Html\HtmlFacade::class);

        $this->app->singleton('Crypto', function ($app) {
            return new Crypto($app);
        });

        $loader = AliasLoader::getInstance();

        $loader->alias('Crypto', \Tks\Larakits\Utilities\Crypto::class);
        $loader->alias('Markdown', \AlfredoRamos\ParsedownExtra\Facades\ParsedownExtra::class);

        // Thrid party
        $loader->alias('Form', \Collective\Html\FormFacade::class);
        $loader->alias('HTML', \Collective\Html\HtmlFacade::class);

        /*
        |--------------------------------------------------------------------------
        | Blade Directives
        |--------------------------------------------------------------------------
        */

        // Crypto
        Blade::directive('crypto_encrypt', function($expression) {
            return "<?php echo Crypto::encrypt$expression; ?>";
        });

        Blade::directive('crypto_decrypt', function($expression) {
            return "<?php echo Crypto::encrypt$expression; ?>";
        });

        /*
        |--------------------------------------------------------------------------
        | Register the Commands
        |--------------------------------------------------------------------------
        */

        $this->commands([
            \Tks\Larakits\Console\Api::class,
            \Tks\Larakits\Console\Billing::class,
            \Tks\Larakits\Console\Notifications::class,
            \Tks\Larakits\Console\Socialite::class,
            \Tks\Larakits\Console\Docs::class,
            \Tks\Larakits\Console\Crud::class,
            \Tks\Larakits\Console\TableCrud::class,
            \Tks\Larakits\Console\Starter::class,
        ]);
    }
}
