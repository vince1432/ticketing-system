<?php

namespace App\Repositories;

use App\Contract\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function all($count = 10)
    {
        return User::select('id', 'name', 'email', 'created_at', 'updated_at')->paginate($count);
    }

    public function get($id)
    {
        return User::select('id', 'name', 'email', 'created_at', 'updated_at')
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
