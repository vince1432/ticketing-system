<?php

namespace App\Contract;

use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function login(array $request);

    public function logout(Request $request);

    public function refresh(Request $request);
}
