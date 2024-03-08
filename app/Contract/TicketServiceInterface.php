<?php

namespace App\Contract;

use Illuminate\Http\Request;

interface TicketServiceInterface extends BaseServiceInterface
{
    public function close(array $validated, $id);

    public function comments(array $validated);

    public function comment($id);

    public function addComment(array $validated);

    public function updateComment(array $validated, $id);

    public function removeComment($id);
}
