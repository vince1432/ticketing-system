<?php

namespace App\Services;

use App\Contract\AuthRepositoryInterface;
use App\Contract\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{

    private $auth_service;
    public $status = 200;

    public function __construct( AuthRepositoryInterface $auth_service) {
        $this->auth_service = $auth_service;
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = $this->auth_service->getUser($validated['email']);
        // dd($user);
        if(!$user || Hash::check($validated['password'], $user->password)) {
            $this->status = 401;
            return array();
        }
        else if (Hash::needsRehash($user->password)) {
            $user->password = Hash::make($validated['password']);
            $user->update();
        }

        $token = $user->createToken("Token of " . $user->name)->plainTextToken;
        $refresh_token = $user->createToken("Token of " . $user->name)->plainTextToken;

        return array(
            "user" => $user->toArray(),
            "token" => $token,
            "refresh_token" => $refresh_token
        );
    }

    public function logout(Request $request)
    {
        return $request->user()->tokens()->delete();
    }

    public function refresh(Request $request)
    {
        $request->user()->tokens()->delete();
        $token = $request->user()->createToken("Token of " . $request->user()->name)->plainTextToken;
        $refresh_token = $request->user()->createToken("Token of " . $request->user()->name)->plainTextToken;

        return array(
            "user" => $request->user()->toArray(),
            "token" => $token,
            "refresh_token" => $refresh_token
        );
    }
}
