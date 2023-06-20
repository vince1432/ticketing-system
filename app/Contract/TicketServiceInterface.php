<?php

namespace App\Contract;

use Illuminate\Http\Request;

interface TicketServiceInterface extends BaseServiceInterface
{
    public function close(Request $request, $id);

    public function comments(Request $request);

    public function comment($id);

    public function addComment(Request $request);

    public function updateComment(Request $request, $id);

    public function removeComment($id);
}
