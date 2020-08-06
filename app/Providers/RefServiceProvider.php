<?php

namespace App\Providers;

use App\Services\RefServices;
use Illuminate\Support\ServiceProvider;

class RefServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(RefServices::class, function ($app){
            return new RefServices();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
