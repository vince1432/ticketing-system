<?php

namespace App\Repositories;

use App\Contract\ModuleRepositoryInterface;
use App\Models\Module;
use Illuminate\Support\Arr;

class ModuleRepository implements ModuleRepositoryInterface
{
    public function all($count = 10)
    {
        return Module::select('id', 'name', 'description')
                ->paginate($count);
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
