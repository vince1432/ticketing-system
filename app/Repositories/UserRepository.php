<?php

namespace App\Repositories;

use App\Contract\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function get($id)
    {

    }

    public function insert($id)
    {

    }

    public function update($id)
    {

    }

    public function delete($id)
    {

    }
}
