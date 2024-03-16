<?php

namespace App\Http\Controllers;

use App\Constants\Message;
use App\Constants\RespStat;
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

        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $data = $this->auth_service->login($validated);

        if ($this->auth_service->status === 401)
            $response = array(
                "status" => RespStat::UNAUTHORIZED,
                "message" => Message::INV_CRED
            );
        else
            $response = array(
                "status" => RespStat::SUCCESS,
                "message" => Message::SUCCESS,
                "data" => $data
            );

        return response()->json($response, $this->auth_service->status);
    }

    public function logout(Request $request)
    {
        $this->auth_service->logout($request);
        $response = array(
            "status" => RespStat::SUCCESS,
            "message" => Message::LOGOUT,
        );

        return response()->json($response, $this->auth_service->status);
    }

    public function refresh(Request $request)
    {

    }
}
