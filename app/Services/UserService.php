<?php

namespace App\Services;

use App\Contract\FileServiceInterface;
use App\Contract\RoleServiceInterface;
use App\Contract\UserRepositoryInterface;
use App\Contract\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

class UserService implements UserServiceInterface
{
    private $user_repository;
    private $file_repository;
    private $role_repository;

    // response status
    public $status = 200;

    public function __construct(
        UserRepositoryInterface $user_repository,
        FileServiceInterface $file_repository,
        RoleServiceInterface $role_repository
    ) {
        $this->user_repository = $user_repository;
        $this->file_repository = $file_repository;
        $this->role_repository = $role_repository;
    }

    public function index(int $count = 0)
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

    public function store(array $validated) : array
    {
        $response = [];

        $new_user = $this->user_repository->insert($validated);

        if($new_user->id) {
            $token = $new_user->createToken("Token of " . $new_user["name"])->accessToken;

            $response["user"] = $new_user->toArray();
            $response["token"] = $token;

            if(array_key_exists('image', $validated) && $validated['image']) {
                $file = $this->file_repository->store($validated['image'], $new_user);
                $response["user"]["image"] = $file['url'];
            }
        }

        return $response;
    }

    public function update(array $validated, $user_id)
    {

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
