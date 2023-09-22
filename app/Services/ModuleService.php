<?php

namespace App\Services;

use App\Contract\ModuleRepositoryInterface;
use App\Contract\ModuleServiceInterface;
use Illuminate\Http\Request;

class ModuleService implements ModuleServiceInterface
{
    private $module_repository;
    // response status
    public $status = 200;

    public function __construct( ModuleRepositoryInterface $module_repository) {
        $this->module_repository = $module_repository;
    }

    public function index($count = 0)
    {
        $query_params = request()->query();
        $sort_by = 'id';
        $sort_dir = 'asc';

        // sorting
        if(isset($query_params['sort_by']))
            $sort_by = $query_params['sort_by'];
        if(isset($query_params['sort_dir']))
            $sort_dir = $query_params['sort_dir'];

        $data = $this->module_repository->all($count, [], $sort_by, $sort_dir);
        return $data->toArray();
    }

    public function show($id)
    {
        if(!$this->module_repository->exist($id)) {
            $this->status = 404;
            return array();
        }

        $ticket = $this->module_repository->get($id);

        return $ticket->toArray();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:1|max:15|unique:ticket_statuses,name',
            'description' => 'required|min:1|max:255'
        ]);

        $new_module = $this->module_repository->insert($validated);

        return $new_module->toArray();
    }

    public function update($request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|min:1|max:15|unique:ticket_statuses,name,' . $id,
            'description' => 'required|min:1|max:255',
        ]);

        if(!$this->module_repository->exist($id)) {
            $this->status = 404;
            return array();
        }

        $ticket = $this->module_repository->update($validated, $id);

        return $ticket->toArray();
    }

    public function destroy($id) {
        $ticket = $this->module_repository->delete($id);

        return $ticket;
    }
}
