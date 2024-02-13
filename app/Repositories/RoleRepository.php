<?php

namespace App\Repositories;

use App\Contract\RoleRepositoryInterface;
use App\Models\Role;
use Illuminate\Support\Arr;

class RoleRepository implements RoleRepositoryInterface
{
    /**
     * role list
     *
     * @param  integer $count item count to return
     * @param  array $filters (optional) for search
     * @param  string $col (optional) column name for order
     * @param  string $dir (optional) order direction
     * @return Illuminate\Database\Eloquent\Collection Role list
     */
    public function all($count = 0, $filters = [], $col = 'id', $dir = 'asc')
    {
        $roles = Role::select('id', 'name', 'level')
                    ->orderBy($col, $dir);
        $roles = ($count)
            ? $roles->paginate($count)
            : $roles->get();

        return $roles;
    }

    public function get($id)
    {
        return Role::select('id', 'name', 'level')
                ->where('id', $id)->first();
    }

    public function insert($data)
    {
        $new_data = Role::create([
            "name" => $data["name"],
            "level" => $data["level"],
        ]);

        return $new_data;
    }

    public function update($data, $id)
    {
        $module = Role::select('id', 'name', 'level')
                    ->where('id', $id)->first();

        if(Arr::exists($data, 'name'))
            $module->name = $data['name'];
        if(Arr::exists($data, 'level'))
            $module->level = $data['level'];

        $module->update();

        return $module;
    }

    public function delete($id)
    {
        return Role::where('id', $id)->delete();
    }

    public function exist($id)
    {
        return Role::select('id')->where('id', $id)->exists();
    }
}
