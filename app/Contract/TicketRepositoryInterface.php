<?php

namespace App\Contract;

interface TicketRepositoryInterface extends BaseRepositoryInterface
{
    public function comments($ticket_id, $count);

    public function comment($id);

    public function addComment($data);

    public function updateComment($data, $id);

    public function removeComment($id);

    public function commentExist($id);
}
