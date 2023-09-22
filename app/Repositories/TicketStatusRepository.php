<?php

namespace App\Repositories;

use App\Contract\TicketStatusRepositoryInterface;
use App\Models\TicketStatus;
use Illuminate\Support\Arr;

class TicketStatusRepository implements TicketStatusRepositoryInterface
{
    /**
     * ticket status list
     *
     * @param  integer $count item count to return
     * @param  array $filters (optional) for search
     * @param  string $col (optional) column name for order
     * @param  string $dir (optional) order direction
     * @return Illuminate\Database\Eloquent\Collection Ticket Status list
     */
    public function all($count = 0, $filters = [], $col = 'id', $dir = 'asc')
    {
        $statuses = TicketStatus::select('id', 'name')
                        ->orderBy($col, $dir);
        $statuses = ($count) ? $statuses->paginate($count) : $statuses->get();
        return $statuses;
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
