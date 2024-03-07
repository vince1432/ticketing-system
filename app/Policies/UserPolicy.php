<?php

namespace App\Policies;

use App\Constants\UserType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // user's roles
        $ary_user_roles = collect($user->roles()->pluck('name'));
        // allowed roles
        $ary_alwd_roles = collect([UserType::SUPER_ADMIN, UserType::ADMIN]);
        $intersection = $ary_user_roles->intersect($ary_alwd_roles)->toArray();

        return boolval($intersection);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        // user's roles
        $ary_user_roles = collect($user->roles()->pluck('name'));
        // allowed roles
        $ary_alwd_roles = collect([UserType::SUPER_ADMIN, UserType::ADMIN]);
        $intersection = $ary_user_roles->intersect($ary_alwd_roles)->toArray();

        return boolval($intersection) || ($user->id === $model->id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // user's roles
        $ary_user_roles = collect($user->roles()->pluck('name'));
        // allowed roles
        $ary_alwd_roles = collect([UserType::SUPER_ADMIN, UserType::ADMIN]);
        $intersection = $ary_user_roles->intersect($ary_alwd_roles)->toArray();

        // only superadmin can create an admin

        return boolval($intersection);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        // user's roles
        $ary_user_roles = collect($user->roles()->pluck('name'));
        // allowed roles
        $ary_alwd_roles = collect([UserType::SUPER_ADMIN, UserType::ADMIN]);
        $intersection = $ary_user_roles->intersect($ary_alwd_roles)->toArray();

        return boolval($intersection) || ($user->id === $model->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        // user's roles
        $ary_user_roles = collect($user->roles()->pluck('name'));
        // allowed roles
        $ary_alwd_roles = collect([UserType::SUPER_ADMIN, UserType::ADMIN]);
        $intersection = $ary_user_roles->intersect($ary_alwd_roles)->toArray();

        return boolval($intersection);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
