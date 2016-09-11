<?php

namespace Tks\Larakits;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
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
        $loader = AliasLoader::getInstance();
        $loader->alias('FormMaker', \Tks\Larakits\Facades\FormMaker::class);
        // Thrid party
        $loader->alias('Form', \Collective\Html\FormFacade::class);
        $loader->alias('HTML', \Collective\Html\HtmlFacade::class);



        $loader = AliasLoader::getInstance();

        // Thrid party
        $loader->alias('Form', \Collective\Html\FormFacade::class);
        $loader->alias('HTML', \Collective\Html\HtmlFacade::class);


        /*
        |--------------------------------------------------------------------------
        | Register the Commands
        |--------------------------------------------------------------------------
        */

        $this->commands([
            \Tks\Larakits\Console\Api::class,
            \Tks\Larakits\Console\Crud::class,
            \Tks\Larakits\Console\Starter::class,
        ]);
    }
}
