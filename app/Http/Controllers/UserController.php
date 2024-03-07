<?php

namespace App\Http\Controllers;

use App\Contract\UserServiceInterface;
use App\Models\User;

class UserController extends BaseController
{
    private $user_service;

    /**
     * set service to be used.
     *
     * @param  App\Contract\UserServiceInterface  $user_service
     * @return void
     */
    public function __construct( UserServiceInterface $user_service)
    {
        $this->user_service = $user_service;
        $this->model = User::class;
        $this->model_string = "User";
        // set base controller service
        $this->setService($this->user_service);
    }
}
