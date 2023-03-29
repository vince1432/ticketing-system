<?php

namespace App\Contract;

interface AuthRepositoryInterface
{
    public function getUser($email);
}
