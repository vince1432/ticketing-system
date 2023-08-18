<?php

namespace App\Repositories;

use App\Contract\TicketRepositoryInterface;
use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Support\Arr;

class TicketRepository implements TicketRepositoryInterface
{
    public function all($count = 0)
    {
        $tickets = Ticket::select('id', 'title', 'summary', 'resolution', 'status_id', 'priority_id',
                    'module_id', 'assigned_to', 'closed_by', 'closed_at', 'created_at', 'updated_at')
                    ->with('priority', 'assignedTo', 'closedBy', 'status', 'module');
        $tickets = ($count) ? $tickets->paginate($count) : $tickets->get();
        return $tickets;
    }

    public function get($id)
    {
        return Ticket::select('id', 'title', 'summary', 'resolution', 'status_id', 'priority_id',
                'module_id', 'assigned_to', 'closed_by', 'closed_at', 'created_at', 'updated_at')
                ->with('priority', 'assignedTo', 'closedBy', 'status', 'module')
                ->where('id', $id)->first();
    }

    public function insert($data)
    {
        $new_data = Ticket::create([
            "title" => $data["title"],
            "assigned_to" => $data["assigned_to"],
            "priority_id" => $data["priority_id"],
            "status_id" => $data["status_id"],
            "module_id" => $data["module_id"],
            "summary" => $data["summary"]
        ]);

        return $new_data;
    }

    public function update($data, $id)
    {
        $ticket = Ticket::select('id', 'title', 'summary', 'status_id', 'priority_id',
                    'module_id', 'assigned_to', 'resolution', 'closed_by', 'closed_at')
                    ->where('id', $id)->first();
        if(Arr::exists($data, 'title'))
            $ticket->title = $data['title'];
        if(Arr::exists($data, 'summary'))
            $ticket->summary = $data['summary'];
        if(Arr::exists($data, 'priority_id'))
            $ticket->priority_id = $data['priority_id'];
        if(Arr::exists($data, 'status_id'))
            $ticket->status_id = $data['status_id'];
        if(Arr::exists($data, 'module_id'))
            $ticket->module_id = $data['module_id'];
        if(Arr::exists($data, 'assigned_to'))
            $ticket->assigned_to = $data['assigned_to'];
        if(Arr::exists($data, 'resolution'))
            $ticket->resolution = $data['resolution'];
        if(Arr::exists($data, 'closed_by'))
            $ticket->closed_by = $data['closed_by'];
        if(Arr::exists($data, 'closed_at'))
            $ticket->closed_at = $data['closed_at'];

        $ticket->update();

        return $ticket;
    }

    public function delete($id)
    {
        return Ticket::where('id', $id)->delete();
    }

    public function count($user_id = 0, $start_date = 0, $end_date = 0)
    {
        $count = Ticket::selectRaw('COUNT(closed_at IS NULL) AS open')
                ->selectRaw('COUNT(closed_at IS NOT NULL) AS closed')
                ->first();

        if($user_id)
            $count = $count->where('assigned_to', $user_id);

        if($start_date)
            $count = $count->whereBetweenColumns($start_date, ['created_at', $start_date]);

        return;
    }

    public function exist($id)
    {
        return Ticket::select('id')->where('id', $id)->exists();
    }

    public function comments($ticket_id, $count = 0)
    {
        $comments = TicketComment::select('id', 'ticket_id', 'commenter_id', 'comment', 'created_at')
        ->with('commenter')
        ->where('ticket_id', $ticket_id);

        $comments = ($count)
            ? $comments->paginate($count)
            : $comments->get();

        return $comments;
    }

    public function comment($id)
    {
        return TicketComment::select('id', 'ticket_id', 'commenter_id', 'comment', 'created_at', 'updated_at')
                ->with('commenter')
                ->where('id', $id)
                ->first();
    }

    public function addComment($data)
    {
        $new_data = TicketComment::create([
            "ticket_id" => $data["ticket_id"],
            "commenter_id" => $data["commenter_id"],
            "comment" => $data["comment"],
        ]);

        return $new_data;
    }

    public function updateComment($data, $id)
    {
        $comment = TicketComment::select('id', 'comment')
                ->where('id', $id)
                ->first();

        $comment->comment = $data['comment'];
        $comment->update();

        return $comment;
    }

    public function removeComment($id)
    {
        $comment = TicketComment::where('id', $id)->delete();

        return $comment;
    }

    public function commentExist($id)
    {
        return TicketComment::select('id')->where('id', $id)->exists();
    }
}
