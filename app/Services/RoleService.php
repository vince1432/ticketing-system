<?php

namespace App\Services;

use App\Contract\RoleRepositoryInterface;
use App\Contract\RoleServiceInterface;
use Illuminate\Http\Request;

class RoleService implements RoleServiceInterface
{
    private $role_repository;
    // response status
    public $status = 200;

    public function __construct( RoleRepositoryInterface $role_repository) {
        $this->role_repository = $role_repository;
    }

    public function index(int $count = 0)
    {
        $query_params = request()->query();
        $sort_by = 'id';
        $sort_dir = 'asc';

        // sorting
        if(isset($query_params['sort_by']))
            $sort_by = $query_params['sort_by'];
        if(isset($query_params['sort_dir']))
            $sort_dir = $query_params['sort_dir'];

        $data = $this->role_repository->all($count, [], $sort_by, $sort_dir);
        return $data->toArray();
    }

    public function show(int $id)
    {
        if(!$this->role_repository->exist($id)) {
            $this->status = 404;
            return array();
        }

        $ticket = $this->role_repository->get($id);

        return $ticket->toArray();
    }

    public function store(array $validated)
    {
        $new_module = $this->role_repository->insert($validated);

        return $new_module->toArray();
    }

    public function update(array $validated, $id)
    {
        if(!$this->role_repository->exist($id)) {
            $this->status = 404;
            return array();
        }

        $ticket = $this->role_repository->update($validated, $id);

        return $ticket->toArray();
    }

    public function destroy($id) {
        $ticket = $this->role_repository->delete($id);

        return $ticket;
    }
}
