<?php

namespace App\Repositories;

use App\Contract\ModuleRepositoryInterface;
use App\Models\Module;
use Illuminate\Support\Arr;

class ModuleRepository implements ModuleRepositoryInterface
{
    /**
     * module list
     *
     * @param  integer $count item count to return
     * @param  array $filters (optional) for search
     * @param  string $col (optional) column name for order
     * @param  string $dir (optional) order direction
     * @return Illuminate\Database\Eloquent\Collection Module list
     */
    public function all($count = 0, $filters = [], $col = 'id', $dir = 'asc')
    {
        $modules = Module::select('id', 'name', 'description')
                    ->orderBy($col, $dir);
        $modules = ($count)
            ? $modules->paginate($count)
            : $modules->get();

        return $modules;
    }

    public function get($id)
    {
        return Module::select('id', 'name', 'description')
                ->where('id', $id)->first();
    }

    public function insert($data)
    {
        $new_data = Module::create([
            "name" => $data["name"],
            "description" => $data["description"],
        ]);

        return $new_data;
    }

    public function update($data, $id)
    {
        $module = Module::select('id', 'name', 'description')
                    ->where('id', $id)->first();

        if(Arr::exists($data, 'name'))
            $module->name = $data['name'];
        if(Arr::exists($data, 'description'))
            $module->description = $data['description'];

        $module->update();

        return $module;
    }

    public function delete($id)
    {
        return Module::where('id', $id)->delete();
    }

    public function exist($id)
    {
        return Module::select('id')->where('id', $id)->exists();
    }
}
