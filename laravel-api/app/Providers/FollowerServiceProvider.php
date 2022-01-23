<?php

namespace App\Providers;

use App\Services\Contracts\FollowerServiceContract;
use App\Services\FollowerService;
use Illuminate\Support\ServiceProvider;

class FollowerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            FollowerServiceContract::class,
            FollowerService::class
        );
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

    public function provides()
    {
        return [
            FollowerService::class,
        ];
    }
}
