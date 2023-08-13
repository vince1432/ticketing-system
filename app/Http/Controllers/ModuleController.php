<?php

namespace App\Http\Controllers;

use App\Contract\ModuleServiceInterface;
use Illuminate\Http\Request;

class ModuleController extends BaseController
{
    private $module_service;

    /**
     * set service to be used.
     *
     * @param  App\Contract\ModuleServiceInterface  $module_service
     * @return void
     */
    public function __construct(ModuleServiceInterface $module_service)
    {
        $this->module_service = $module_service;
        $this->model = "Module";
        // set base controller service
        $this->setService($this->module_service);
    }
}
