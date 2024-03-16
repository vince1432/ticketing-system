<?php

namespace App\Policies;

use App\Constants\UserType;
use App\Models\Role;
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
        $clc_user_roles = collect($user->roles()->pluck('name'));
        $clc_model_roles = collect($model->roles()->pluck('name'));
        // allowed roles
        $ary_alwd_roles = [UserType::SUPER_ADMIN];
        if(!$clc_model_roles->contains(UserType::ADMIN) && !$clc_model_roles->contains(UserType::SUPER_ADMIN))
            $ary_alwd_roles[] = UserType::ADMIN;

        $clc_alwd_roles = collect($ary_alwd_roles);
        $intersection = $clc_user_roles->intersect($clc_alwd_roles)->toArray();

        return boolval($intersection) || ($user->id === $model->id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  array  $roles
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, array $roles)
    {
        // user's roles
        $ary_user_roles = collect($user->roles()->pluck('name'));
        // allowed roles
        $ary_alwd_roles = collect([UserType::SUPER_ADMIN, UserType::ADMIN]);
        $intersection = $ary_user_roles->intersect($ary_alwd_roles)->toArray();

        // only superadmin can create an admin
        $user_roles = Role::select('id', 'name')->whereIn('id', $roles)->pluck('name');

        if(($user_roles->contains(UserType::SUPER_ADMIN) || $user_roles->contains(UserType::ADMIN))
            && !$ary_user_roles->contains(UserType::SUPER_ADMIN))
            return false;

        return boolval($intersection);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model, array $roles)
    {
        // user's roles
        $clc_user_roles = collect($user->roles()->pluck('name'));
        $clc_model_roles = collect($model->roles()->pluck('name'));

        // allowed roles
        $ary_alwd_roles = [UserType::SUPER_ADMIN];
        // only superadmin can update an admin or superadmin
        $user_roles = Role::select('id', 'name')->whereIn('id', $roles)->pluck('name');
        if(
            !$user_roles->contains(UserType::SUPER_ADMIN)
            && !$user_roles->contains(UserType::ADMIN)
            && !$clc_model_roles->contains(UserType::SUPER_ADMIN)
            && !$clc_model_roles->contains(UserType::ADMIN)
        )
            $ary_alwd_roles[] = UserType::ADMIN;

        $clc_alwd_roles = collect($ary_alwd_roles);

        $intersection = $clc_user_roles->intersect($clc_alwd_roles)->toArray();

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
        $clc_model_roles = collect($model->roles()->pluck('name'));
        // allowed roles
        $ary_alwd_roles = [UserType::SUPER_ADMIN];
        if(!$clc_model_roles->contains(UserType::ADMIN) && !$clc_model_roles->contains(UserType::SUPER_ADMIN))
            $ary_alwd_roles[] = UserType::ADMIN;

        $ary_alwd_roles = collect([UserType::SUPER_ADMIN, UserType::ADMIN]);
        $intersection = $ary_user_roles->intersect($ary_alwd_roles)->toArray();

        return boolval($intersection);
    }
}
