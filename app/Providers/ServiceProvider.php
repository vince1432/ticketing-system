<?php

namespace App\Providers;

use App\Contract\TicketServiceInterface;
use App\Services\TicketService;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use App\Contract\UserServiceInterface;
use App\Services\UserService;
use App\Contract\AuthServiceInterface;
use App\Services\AuthService;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TicketServiceInterface::class, TicketService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
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
