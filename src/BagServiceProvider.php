<?php

namespace Laraware\Bag;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class BagServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBag();
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
