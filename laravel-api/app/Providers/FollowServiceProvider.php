<?php

namespace App\Providers;

use App\Services\Contracts\FollowServiceContract;
use App\Services\FollowService;
use Illuminate\Support\ServiceProvider;

class FollowServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            FollowServiceContract::class,
            FollowService::class
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
            FollowService::class,
        ];
    }
}
