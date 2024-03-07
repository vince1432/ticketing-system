<?php

namespace App\Repositories;

use App\Contract\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    /**
     * user list
     *
     * @param  integer $count item count to return
     * @param  array $filters (optional) for search
     * @param  string $col (optional) column name for order
     * @param  string $dir (optional) order direction
     * @return Illuminate\Database\Eloquent\Collection User list
     */
    public function all($count = 0, $filters = [], $col = 'id', $dir = 'asc')
    {
        $users = User::select('id', 'name', 'email', 'created_at', 'updated_at')
                    ->with('roles')
                    ->orderBy($col, $dir);

        // add filter
        if(Arr::exists($filters, 'search'))
            $users = $users->where(function ($query) use ($filters) {
                $query->where('name', 'LIKE', '%' . $filters['search'] . '%')
                      ->orWhere('email', 'LIKE', '%' . $filters['search'] . '%')
                      ->orWhere('id', '=', $filters['search']);
            });

        $users = ($count) ? $users->paginate($count) : $users->get();
        return $users;
    }

    /**
     * Undocumented function
     *
     * @param integer $id
     * @return User
     */
    public function get($id)
    {
        return User::select('id', 'name', 'email', 'created_at', 'updated_at')
                ->with('fileable')
                ->where('id', $id)->first();
    }

    public function insert($user_data)
    {
        $new_user = User::create([
            "name" => $user_data["name"],
            "email" => $user_data["email"],
            "password" => Hash::make($user_data["password"])
        ]);

        return $new_user;
    }

    public function update($user_data, $user_id)
    {
        $user = User::select('id', 'name', 'email', 'created_at', 'updated_at')
                    ->where('id', $user_id)->first();
        if(Arr::exists($user_data, 'name'))
            $user->name = $user_data['name'];
        if(Arr::exists($user_data, 'email'))
            $user->email = $user_data['email'];
        if(Arr::exists($user_data, 'password'))
            $user->password = Hash::make($user_data['password']);

        $user->update();

        return $user;
    }

    public function delete($user_id)
    {
        return User::where('id', $user_id)->delete();
    }



    public function exist($user_id)
    {
        return User::select('id')->where('id', $user_id)->exists($user_id);
    }
}
