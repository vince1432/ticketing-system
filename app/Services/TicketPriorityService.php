<?php

namespace App\Services;

use App\Contract\TicketPriorityRepositoryInterface;
use App\Contract\TicketPriorityServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class TicketPriorityService implements TicketPriorityServiceInterface
{
    private $ticket_priority_repository;
    // response status
    public $status = 200;

    public function __construct( TicketPriorityRepositoryInterface $ticket_priority_repository) {
        $this->ticket_priority_repository = $ticket_priority_repository;
    }

    public function index($count = 10)
    {
        $data = $this->ticket_priority_repository->all($count);
        return $data->toArray();
    }

    public function show($id)
    {
        if(!$this->ticket_priority_repository->exist($id)) {
            $this->status = 404;
            return array();
        }

        $ticket = $this->ticket_priority_repository->get($id);

        return $ticket->toArray();
    }

    public function store($request)
    {
        $validated = $request->validate([
            'level' => 'required|integer|unique:ticket_prioties|level',
            'name' => 'required|min:1|max:15|unique:ticket_prioties|name',
        ]);

        $new_ticket = $this->ticket_priority_repository->insert($validated);

        return $new_ticket->toArray();
    }

    public function update($request, $id)
    {
        $validated = $request->validate([
            'level' => 'required|integer|unique:ticket_prioties|level,' .$id,
            'name' => 'required|min:1|max:15|unique:ticket_prioties|name,' .$id,
        ]);

        if(!$this->ticket_priority_repository->exist($id)) {
            $this->status = 404;
            return array();
        }

        $ticket = $this->ticket_priority_repository->update($validated, $id);

        return $ticket->toArray();
    }

    public function destroy($id) {
        $ticket = $this->ticket_priority_repository->delete($id);

        return $ticket;
    }
}
