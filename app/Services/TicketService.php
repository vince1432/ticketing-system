<?php

namespace App\Services;

use App\Contract\TicketRepositoryInterface;
use App\Contract\TicketServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class TicketService implements TicketServiceInterface
{
    private $ticket_repository;
    // response status
    public $status = 200;

    public function __construct( TicketRepositoryInterface $ticket_repository) {
        $this->ticket_repository = $ticket_repository;
    }

    public function index(int $count = 0)
    {
        $query_params = request()->query();
        $filters = [];
        $sort_by = 'id';
        $sort_dir = 'asc';

        // filter params
        if(isset($query_params['search']))
            $filters['search'] = $query_params['search'];
        if(isset($query_params['module']))
            $filters['module'] = $query_params['module'];
        if(isset($query_params['priority']))
            $filters['priority'] = $query_params['priority'];
        if(isset($query_params['status']))
            $filters['status'] = $query_params['status'];
        if(isset($query_params['assigned']))
            $filters['assigned'] = $query_params['assigned'];
        if(isset($query_params['start_date']))
            $filters['start_date'] = $query_params['start_date'];
        if(isset($query_params['end_date']))
            $filters['end_date'] =  $query_params['end_date'];

        // sorting
        if(isset($query_params['sort_by']))
            $sort_by = $query_params['sort_by'];
        if(isset($query_params['sort_dir']))
            $sort_dir = $query_params['sort_dir'];

        $data = $this->ticket_repository->all($count, $filters, $sort_by, $sort_dir);
        return $data->toArray();
    }

    public function show(int $id)
    {
        if(!$this->ticket_repository->exist($id)) {
            $this->status = 404;
            return array();
        }

        $ticket = $this->ticket_repository->get($id);

        return $ticket->toArray();
    }

    public function store(array $validated)
    {
        $new_ticket = $this->ticket_repository->insert($validated);

        return $new_ticket->toArray();
    }

    public function update(array $validated, $id)
    {
        if(!$this->ticket_repository->exist($id)) {
            $this->status = 404;
            return array();
        }

        $ticket = $this->ticket_repository->update($validated, $id);

        return $ticket->toArray();
    }

    public function destroy($id) {
        $ticket = $this->ticket_repository->delete($id);

        return $ticket;
    }

    public function close(array $validated, $id) {

        if(!$this->ticket_repository->exist($id)) {
            $this->status = 404;
            return array();
        }

        $validated = array();
        $validated['closed_by'] = auth()->user()->id;
        $validated['closed_at'] = Carbon::now();

        $ticket = $this->ticket_repository->update($validated, $id);

        return $ticket->toArray();
    }

    public function comments(array $validated)
    {
        $query_params = request()->query();
        $item_count = $query_params['item_count'] ?? 0;
        $sort_by = 'id';
        $sort_dir = 'asc';

        // sorting
        if(isset($query_params['sort_by']))
            $sort_by = $query_params['sort_by'];
        if(isset($query_params['sort_dir']))
            $sort_dir = $query_params['sort_dir'];

        $data = $this->ticket_repository->comments($validated['ticket_id'], $item_count, $sort_by, $sort_dir);

        return $data->toArray();
    }

    public function comment($id)
    {
        if (!$this->ticket_repository->commentExist($id)) {
            $this->status = 404;
            return array();
        }

        $comment = $this->ticket_repository->comment($id);

        return $comment->toArray();
    }

    public function addComment(array $validated)
    {
        if(!$this->ticket_repository->exist($validated['ticket_id'])) {
            $this->status = 404;
            return array();
        }

        $validated['commenter_id'] = auth()->user()->id;

        $comment = $this->ticket_repository->addComment($validated);
        $this->status = 201;

        return $comment->toArray();
    }

    public function updateComment($validated, $id)
    {
        if(!$this->ticket_repository->commentExist($id)) {
            $this->status = 404;
            return array();
        }

        $comment = $this->ticket_repository->updateComment($validated, $id);

        return $comment->toArray();
    }

    public function removeComment($id)
    {
        $comment = $this->ticket_repository->removeComment($id);

        return $comment;
    }
}
