<?php

namespace App\Http\Controllers;

use App\Contract\RoleServiceInterface;

class RoleController extends BaseController
{
    private $role_service;

    /**
     * set service to be used.
     *
     * @param  App\Contract\RoleServiceInterface  $role_service
     * @return void
     */
    public function __construct(RoleServiceInterface $role_service)
    {
        $this->role_service = $role_service;
        $this->model = "Role";
        // set base controller service
        $this->setService($this->role_service);
    }
}
