<?php

namespace Laraware\Bag;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class BagServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerConfig();
        $this->publishesConfig();
    }

    public function register()
    {
        $this->registerBag();
    }

    protected function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/shopping-bag.php', 'shopping-bag');
    }

    protected function publishesConfig()
    {
        $this->publishes([
            __DIR__.'/../config/shopping-bag.php' => config_path('shopping-bag.php'),
        ]);
    }

    protected function registerBag()
    {
        $this->app->singleton('bag', function (Container $app) {
            return new Bag($app['session']);
        });

        $this->app->alias('bag', Bag::class);
    }

    public function provides()
    {
        return ['bag'];
    }
}
