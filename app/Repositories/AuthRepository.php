<?php

namespace App\Repositories;

use App\Contract\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function getUser($email)
    {
        $user = User::select('id', 'name', 'email', 'created_at', 'updated_at')
                    ->where('email', $email)->first();

        return $user;
    }
}
