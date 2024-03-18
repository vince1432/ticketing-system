<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Constants\UserType;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function register()
    {

    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // gates
        Gate::define('is-admin', function (User $user) {
            // user's roles
            $ary_user_roles = collect($user->roles()->pluck('name'));
            // allowed roles
            $ary_alwd_roles = collect([UserType::SUPER_ADMIN, UserType::ADMIN]);
            $intersection = $ary_user_roles->intersect($ary_alwd_roles)->toArray();

            return boolval($intersection);
        });
    }
}
