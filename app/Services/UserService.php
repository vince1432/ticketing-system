<?php

namespace App\Services;

use App\Contract\UserRepositoryInterface;
use App\Contract\UserServiceInterface;

class UserService implements UserServiceInterface
{

    private $user_repository;

    public function __construct( UserRepositoryInterface $user_repository) {
        $this->user_repository = $user_repository;
    }

    public function index()
    {
        return $this->user_repository->all();
    }

    public function show($id) {

    }

    public function store($id) {

    }

    public function update($id) {

    }

    public function destroy($id) {

    }
}
