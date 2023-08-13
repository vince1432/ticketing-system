<?php

namespace App\Repositories;

use App\Contract\TicketStatusRepositoryInterface;
use App\Models\TicketStatus;
use Illuminate\Support\Arr;

class TicketStatusRepository implements TicketStatusRepositoryInterface
{
    public function all($count = 10)
    {
        return TicketStatus::select('id', 'name')
                ->paginate($count);
    }

    public function get($id)
    {
        return TicketStatus::select('id', 'name')
                ->where('id', $id)->first();
    }

    public function insert($data)
    {
        $new_data = TicketStatus::create([
            "name" => $data["name"],
        ]);

        return $new_data;
    }

    public function update($data, $id)
    {
        $status = TicketStatus::select('id', 'name')
                    ->where('id', $id)->first();

        if(Arr::exists($data, 'name'))
            $status->name = $data['name'];

        $status->update();

        return $status;
    }

    public function delete($id)
    {
        return TicketStatus::where('id', $id)->delete();
    }

    public function exist($id)
    {
        return TicketStatus::select('id')->where('id', $id)->exists();
    }
}
