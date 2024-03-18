<?php

namespace App\Policies;

use App\Constants\UserType;
use App\Models\Role;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketCommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TicketComment  $ticketComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TicketComment $ticketComment)
    {
        // user's roles
        $clc_user_roles = collect($user->roles()->pluck('name'));
        $clc_model_roles = collect($ticketComment->commenter->roles)->pluck('name');

        // allowed roles
        $ary_alwd_roles = [UserType::SUPER_ADMIN];
        // only superadmin can update an admin or superadmin
        if(!$clc_model_roles->contains(UserType::SUPER_ADMIN)
            && !$clc_model_roles->contains(UserType::ADMIN))
            $ary_alwd_roles[] = UserType::ADMIN;

        $clc_alwd_roles = collect($ary_alwd_roles);

        $intersection = $clc_user_roles->intersect($clc_alwd_roles)->toArray();

        return boolval($intersection) || ($user->id === $ticketComment->commenter_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TicketComment  $ticketComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TicketComment $ticketComment)
    {
        // user's roles
        $clc_user_roles = collect($user->roles()->pluck('name'));
        $clc_model_roles = collect($ticketComment->commenter->roles)->pluck('name');

        // allowed roles
        $ary_alwd_roles = [UserType::SUPER_ADMIN];
        // only superadmin can update an admin or superadmin
        if(!$clc_model_roles->contains(UserType::SUPER_ADMIN)
            && !$clc_model_roles->contains(UserType::ADMIN))
            $ary_alwd_roles[] = UserType::ADMIN;

        $clc_alwd_roles = collect($ary_alwd_roles);

        $intersection = $clc_user_roles->intersect($clc_alwd_roles)->toArray();

        return boolval($intersection) || ($user->id === $ticketComment->commenter_id);
    }
}
