<?php

namespace App\Repositories;

use App\Contract\TicketPriorityRepositoryInterface;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketPrioty;
use Illuminate\Support\Arr;

class TicketPriorityRepository implements TicketPriorityRepositoryInterface
{
    /**
     * ticket priority list
     *
     * @param  integer $count item count to return
     * @param  array $filters (optional) for search
     * @param  string $col (optional) column name for order
     * @param  string $dir (optional) order direction
     * @return Illuminate\Database\Eloquent\Collection Ticket Priority list
     */
    public function all($count = 0, $filters = [], $col = 'id', $dir = 'asc')
    {
        $priorities = TicketPrioty::select('id', 'level', 'name')
                        ->orderBy($col, $dir);
        $priorities = ($count) ? $priorities->paginate($count) : $priorities->get();
        return $priorities;
    }

    public function get($id)
    {
        return TicketPrioty::select('id', 'level', 'name')
                ->where('id', $id)->first();
    }

    public function insert($data)
    {
        $new_data = TicketPrioty::create([
            "level" => $data["level"],
            "name" => $data["name"],
        ]);

        return $new_data;
    }

    public function update($data, $id)
    {
        $ticket_priority = TicketPrioty::select('id', 'level', 'name')
                    ->where('id', $id)->first();

        if(Arr::exists($data, 'level'))
            $ticket_priority->level = $data['level'];
        if(Arr::exists($data, 'name'))
            $ticket_priority->name = $data['name'];

        $ticket_priority->update();

        return $ticket_priority;
    }

    public function delete($id)
    {
        return TicketPrioty::where('id', $id)->delete();
    }

    public function exist($id)
    {
        return TicketPrioty::select('id')->where('id', $id)->exists();
    }

}
