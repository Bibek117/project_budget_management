<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\CommonInterface;
use App\Repostories\CommonInterfaceRepository;
use App\Repostories\ProjectRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CommonInterface::class,CommonInterfaceRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
