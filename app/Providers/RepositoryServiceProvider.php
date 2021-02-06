<?php

namespace App\Providers;

use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Repositories\PostRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
            //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            PostRepositoryInterface::class,
            PostRepository::class
        );
    }
}
