<?php

namespace App\Services;

use App\Contract\UserRepositoryInterface;
use App\Contract\UserServiceInterface;
use Illuminate\Support\Facades\Storage;
use Image;

class UserService implements UserServiceInterface
{
    private $user_repository;
    // response status
    public $status = 200;

    public function __construct( UserRepositoryInterface $user_repository) {
        $this->user_repository = $user_repository;
    }

    public function index($count = 0)
    {
        $query_params = request()->query();
        $filters = [];
        $sort_by = 'id';
        $sort_dir = 'asc';

        // filter params
        if(isset($query_params['search']))
            $filters['search'] = $query_params['search'];

         // sorting
        if(isset($query_params['sort_by']))
            $sort_by = $query_params['sort_by'];
        if(isset($query_params['sort_dir']))
            $sort_dir = $query_params['sort_dir'];

        $data = $this->user_repository->all($count, $filters, $sort_by, $sort_dir);
        return $data->toArray();
    }

    public function show($user_id) {

        if(!$this->user_repository->exist($user_id)) {
            $this->status = 404;
            return array();
        }

        $user = $this->user_repository->get($user_id);

        return $user->toArray();
    }

    public function store($request) {
        $validated = $request->validate([
            'name' => 'max:255',
            'email' => 'required|unique:users,email|max:255|email',
            'password' => 'required|min:6|max:50|confirmed',
        ]);

        $new_user = $this->user_repository->insert($validated);

        $token = $new_user->createToken("Token of " . $new_user["name"])->accessToken;

        $response = array(
            "user" => $new_user->toArray(),
            "token" => $token
        );

        return $response;
    }

    public function update($request, $user_id) {
        $validated = $request->validate([
            'name' => 'nullable|max:255',
            'email' => 'nullable|max:255|email|unique:users,email,' . $user_id,
            'password' => 'nullable|min:6|max:50|confirmed',
        ]);

        if(!$this->user_repository->exist($user_id)) {
            $this->status = 404;
            return array();
        }

        $user = $this->user_repository->update($validated, $user_id);

        return $user->toArray();
    }

    public function destroy($user_id) {
        $user = $this->user_repository->delete($user_id);

        return $user;
    }
}
