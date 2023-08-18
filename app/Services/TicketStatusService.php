<?php

namespace App\Services;

use App\Contract\TicketStatusRepositoryInterface;
use App\Contract\TicketStatusServiceInterface;

class TicketStatusService implements TicketStatusServiceInterface
{
    private $ticket_status_repository;
    // response status
    public $status = 200;

    public function __construct( TicketStatusRepositoryInterface $ticket_status_repository) {
        $this->ticket_status_repository = $ticket_status_repository;
    }

    public function index($count = 0)
    {
        $data = $this->ticket_status_repository->all($count);
        return $data->toArray();
    }

    public function show($id)
    {
        if(!$this->ticket_status_repository->exist($id)) {
            $this->status = 404;
            return array();
        }

        $ticket = $this->ticket_status_repository->get($id);

        return $ticket->toArray();
    }

    public function store($request)
    {
        $validated = $request->validate([
            'name' => 'required|min:1|max:15|unique:ticket_statuses,name'
        ]);

        $new_ticket_status = $this->ticket_status_repository->insert($validated);

        return $new_ticket_status->toArray();
    }

    public function update($request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|min:1|max:15|unique:ticket_statuses,name,' . $id,
        ]);

        if(!$this->ticket_status_repository->exist($id)) {
            $this->status = 404;
            return array();
        }

        $ticket = $this->ticket_status_repository->update($validated, $id);

        return $ticket->toArray();
    }

    public function destroy($id) {
        $ticket = $this->ticket_status_repository->delete($id);

        return $ticket;
    }
}
