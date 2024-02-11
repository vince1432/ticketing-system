<?php

namespace App\Providers;

use App\Contract\TicketRepositoryInterface;
use App\Repositories\TicketRepository;
use Illuminate\Support\ServiceProvider;
use App\Contract\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Contract\AuthRepositoryInterface;
use App\Contract\FileRepositoryInterface;
use App\Contract\ModuleRepositoryInterface;
use App\Contract\TicketPriorityRepositoryInterface;
use App\Contract\TicketStatusRepositoryInterface;
use App\Repositories\AuthRepository;
use App\Repositories\FileRepository;
use App\Repositories\ModuleRepository;
use App\Repositories\TicketPriorityRepository;
use App\Repositories\TicketStatusRepository;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(TicketPriorityRepositoryInterface::class, TicketPriorityRepository::class);
        $this->app->bind(TicketStatusRepositoryInterface::class, TicketStatusRepository::class);
        $this->app->bind(ModuleRepositoryInterface::class, ModuleRepository::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
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
