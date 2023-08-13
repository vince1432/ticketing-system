<?php

namespace App\Providers;

use App\Contract\TicketServiceInterface;
use App\Services\TicketService;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use App\Contract\UserServiceInterface;
use App\Services\UserService;
use App\Contract\AuthServiceInterface;
use App\Contract\ModuleServiceInterface;
use App\Contract\TicketPriorityServiceInterface;
use App\Contract\TicketStatusServiceInterface;
use App\Services\AuthService;
use App\Services\ModuleService;
use App\Services\TicketPriorityService;
use App\Services\TicketStatusService;

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
        $this->app->bind(TicketPriorityServiceInterface::class, TicketPriorityService::class);
        $this->app->bind(TicketStatusServiceInterface::class, TicketStatusService::class);
        $this->app->bind(ModuleServiceInterface::class, ModuleService::class);
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
