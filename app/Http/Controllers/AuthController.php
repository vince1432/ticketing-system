<?php

namespace App\Http\Controllers;

use App\Contract\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $auth_service;

    public function __construct(AuthServiceInterface $auth_service)
    {
        $this->auth_service = $auth_service;
    }

    public function login(Request $request)
    {
        $data = $this->auth_service->login($request);

        if ($this->auth_service->status === 401)
            $response = array(
                "status" => "Unauthorized",
                "message" => "Invalid login credentials."
            );
        else
            $response = array(
                "status" => "Success",
                "message" => "Success.",
                "data" => $data
            );

        return response()->json($response, $this->auth_service->status);
    }

    public function logout(Request $request)
    {
        $this->auth_service->logout($request);
        $response = array(
            "status" => "Success",
            "message" => "Successfuly logged out.",
        );

        return response()->json($response, $this->auth_service->status);
    }

    public function refresh(Request $request)
    {

    }
}
